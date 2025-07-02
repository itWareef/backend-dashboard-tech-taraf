<?php

namespace App\Http\Controllers\Api;

use App\Core\Classes\HandleFiles\StoragePictures;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupervisorRequests\AcceptOrRejectRequestRequest;
use App\Http\Requests\SupervisorRequests\AnotherVisitRequestRequest;
use App\Http\Requests\SupervisorRequests\FinishRequestRequest;
use App\Http\Requests\SupervisorRequests\SupervisorLoginRequest;

use App\Http\Requests\SupervisorRequests\UpdatingRequestRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Models\Requests\MaintenanceRequest;
use App\Models\Requests\PlantingRequest;
use App\Models\Requests\SuperVisorRequests;
use App\Models\Supervisor;
use App\Services\SuperVisorServices\SupervisorAuthService;

use App\Services\SuperVisorServices\SuperVisorUpdatingRequestService;
use App\Services\SupervisorServices\SupervisorUpdatingService;
use App\Services\SupervisorServices\RegisterSupervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Rap2hpoutre\FastExcel\FastExcel;
use Spatie\QueryBuilder\QueryBuilder;

class SupervisorController extends Controller
{

    public function register()
    {
        return (new RegisterSupervisor())->storeNewRecord() ;
    }

    public function update(Supervisor $supervisor)
    {
        return (new SupervisorUpdatingService($supervisor))->update() ;
    }
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Supervisor::class)->allowedFilters([
            'first_name',
            'last_name',
            'name',
            'username',
            'email',
            'phone',
            'date_of_birth',
            'expire_identify',
        ])->datesFiltering()->paginate(7);
        $statistics = (new SupervisorStatistics())->getStatistics();
        $total = Supervisor::count();
        return \response()->json(['list' =>$data ,'statistics' =>$statistics ,'total' =>$total]);
    }

    public function show(Request $request ,Supervisor $supervisor)
    {
        $data = $supervisor->toArray();
        return Response::success($data);
    }
    public function list(Request $request)
    {
        $data = QueryBuilder::for(Supervisor::class)->allowedFilters([
            'name',
        ])->get(['id' ,'name','picture'])->toArray();
        return Response::success($data);
    }

    public function login(SupervisorLoginRequest $request)
    {
        return (new SupervisorAuthService())->login($request);
    }
    public function verifyOtp(VerifyOtpRequest $request)
    {
        return (new SupervisorAuthService())->verifyOtp($request);
    }

    public function logout(Request $request)
    {
        $request->user('supervisor')->token()->revoke();
        return Response::success([], ['Successfully logged out'] , 200);
    }
    public function me(Request $request)
    {
        $supervisor = $request->user('supervisor');
        $data = $supervisor->toArray();
        $supervisorId = $supervisor->id;

        $requestType = $supervisor->type === 'planting'
            ? PlantingRequest::class
            : MaintenanceRequest::class;

        $data['counting'] = [
            'requests' => $this->getSupervisorRequestCount($requestType, $supervisorId, SuperVisorRequests::PENDING, $requestType::IN_PROGRESS),
            'in_progress' => $this->getSupervisorRequestCount($requestType, $supervisorId, SuperVisorRequests::ACCEPTED, $requestType::IN_PROGRESS),
            'finished' => QueryBuilder::for($requestType)
                ->where('status', $requestType::FINISHED)
                ->whereHas('supervisors', function ($query) use ($supervisorId) {
                    $query->where('status', SuperVisorRequests::ACCEPTED)
                        ->where('supervisor_id', $supervisorId);
                })
                ->count(),
        ];

        $data['rating'] = QueryBuilder::for($requestType)
            ->where('status', $requestType::FINISHED)
            ->whereHas('supervisors', function ($query) use ($supervisorId) {
                $query->where('status', SuperVisorRequests::ACCEPTED)
                    ->where('supervisor_id', $supervisorId);
            })
            ->avg('rating')??0;
        return Response::success($data);
    }

    private function getSupervisorRequestCount(string $modelClass, int $supervisorId, string $status , string$status2): int
    {
        return QueryBuilder::for($modelClass)
            ->where('status', $status2)
            ->whereHas('supervisors', function ($query) use ($status, $supervisorId) {
                $query->where('status', $status)
                    ->where('supervisor_id', $supervisorId);
            })
            ->count();
    }

    public function updateProfile(Request $request)
    {
        $supervisor = $request->user('supervisor');
        return (new SupervisorUpdatingService($supervisor))->update() ;
    }

    public function requests()
    {
        $requestType = auth('supervisor')->user()->type == 'planting' ? PlantingRequest::class :MaintenanceRequest::class;
        $data = QueryBuilder::for($requestType)
            ->allowedFilters([])
            ->whereHas('supervisors', function ($query) {
                $query->where('status',SuperVisorRequests::PENDING)->where('supervisor_id',auth('supervisor')->id());
            })
            ->with(['unit','project','requester'])->get();
        return Response::success(['data' =>$data]);
    }
    public function requestsInProgress()
    {
        $requestType = auth('supervisor')->user()->type == 'planting' ? PlantingRequest::class :MaintenanceRequest::class;
        $data = QueryBuilder::for($requestType)
            ->allowedFilters([])
            ->where('status' , $requestType::IN_PROGRESS)
            ->whereHas('supervisors', function ($query) {
                $query->where('status',SuperVisorRequests::ACCEPTED)->where('supervisor_id',auth('supervisor')->id());
            })
            ->with(['unit','project','requester'])->get();
        return Response::success(['data' =>$data]);
    }
    public function requestsFinished()
    {
        $requestType = auth('supervisor')->user()->type == 'planting' ? PlantingRequest::class :MaintenanceRequest::class;
        $data = QueryBuilder::for($requestType)
            ->allowedFilters([])
            ->where('status' , $requestType::FINISHED)->whereHas('supervisors', function ($query) {
                $query->where('status',SuperVisorRequests::ACCEPTED)->where('supervisor_id',auth('supervisor')->id());
            })
            ->with(['unit','project','requester'])->get();
        return Response::success(['data' =>$data]);
    }

    protected function getRequestModel()
    {
        $supervisor = auth('supervisor')->user();
        return $supervisor->type === 'planting' ? PlantingRequest::class : MaintenanceRequest::class;
    }

    public function acceptOrReject(AcceptOrRejectRequestRequest $request, int $id)
    {
        $supervisor = auth('supervisor')->user();
        $requestType = $this->getRequestModel();
        $object = $requestType::findOrFail($id);

        $status = $request->status === SuperVisorRequests::ACCEPTED
            ? SuperVisorRequests::ACCEPTED
            : SuperVisorRequests::REJECTED;

        DB::transaction(function () use ($object, $requestType, $status , $supervisor , $request) {
            $object->supervisors()
                ->where('supervisor_id', $supervisor->id)
                ->update(['status' => $status]);

            $object->superVisorVisits()->create([
                'supervisor_id' => $supervisor->id
            ]);
           if(!empty($request['attachments'])){
               foreach ($request['attachments'] as $attachment) {
                   if (!$attachment instanceof \Illuminate\Http\UploadedFile) {
                       continue;
                   }

                   $storedName = StoragePictures::storeFile($attachment['path'], $object);

                   if ($storedName !== false) {
                       $object->attachments()->create([
                           'path' => $storedName,
                           'original_name' => $attachment->getClientOriginalName(), // إذا كنت تخزن الاسم الأصلي
                       ]);
                   }
               }
           }


        });

        return Response::success([], ['تم استقبال ردك على الطلب']);
    }

    public function finishedRequest(FinishRequestRequest $request, int $id)
    {
        $supervisor = auth('supervisor')->user();
        $requestType = $this->getRequestModel();
        $object = $requestType::findOrFail($id);

        if ($request->otp !== $object->otp) {
            return Response::error('رمز التحقق لا يطابق');
        }

        $object->status = $requestType::WAITING_RATING;
        $object->save();

        $object->load(['supervisors']);
        return (new SuperVisorUpdatingRequestService($object->supervisors))->update();
    }

    public function anotherVisit(AnotherVisitRequestRequest $request, int $id)
    {
        $supervisor = auth('supervisor')->user();
        $requestType = $this->getRequestModel();
        $object = $requestType::findOrFail($id);

        $object->superVisorVisits()
            ->where('supervisor_id', $supervisor->id)
            ->update(['reason' => $request->reason]);

        return Response::success([], ['تم ارسال طلب زيارة بنجاح']);
    }
}
