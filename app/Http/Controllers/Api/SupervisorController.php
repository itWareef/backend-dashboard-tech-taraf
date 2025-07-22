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
use App\Services\SupervisorServices\RegisterSupervisor;
use App\Services\SuperVisorServices\SuperVisorUpdatingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Rap2hpoutre\FastExcel\FastExcel;
use Spatie\QueryBuilder\QueryBuilder;

class SupervisorController extends Controller
{
    /**
     * Register a new supervisor.
     */
    public function register()
    {
        return (new RegisterSupervisor())->storeNewRecord();
    }

    /**
     * Update supervisor data.
     */
    public function update(Supervisor $supervisor)
    {
        return (new SuperVisorUpdatingService($supervisor))->update();
    }

    /**
     * List supervisors with filters and statistics.
     */
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Supervisor::class)
            ->allowedFilters(['first_name', 'last_name', 'name', 'username', 'email', 'phone', 'date_of_birth', 'expire_identify'])
            ->datesFiltering()
            ->paginate(7);

        $statistics = (new \App\Services\SuperVisorServices\SupervisorStatistics())->getStatistics();
        $total = Supervisor::count();

        return response()->json([
            'list' => $data,
            'statistics' => $statistics,
            'total' => $total,
        ]);
    }

    /**
     * Show supervisor details.
     */
    public function show(Request $request, Supervisor $supervisor)
    {
        return Response::success($supervisor->toArray());
    }

    /**
     * Get lightweight list of supervisors (id, name, picture).
     */
    public function list()
    {
        $data = Supervisor::select('id', 'name', 'picture')->get()->toArray();
        return Response::success($data);
    }

    /**
     * Supervisor login.
     */
    public function login(SupervisorLoginRequest $request)
    {
        return (new SupervisorAuthService())->login($request);
    }

    /**
     * Verify supervisor OTP.
     */
    public function verifyOtp(VerifyOtpRequest $request)
    {
        return (new SupervisorAuthService())->verifyOtp($request);
    }

    /**
     * Supervisor logout.
     */
    public function logout(Request $request)
    {
        $request->user('supervisor')->token()->revoke();
        return Response::success([], ['Successfully logged out'], 200);
    }

    /**
     * Get current supervisor info and stats.
     */
    public function me(Request $request)
    {
        $supervisor = $request->user('supervisor');
        $model = $this->getRequestModel();

        $data = $supervisor->toArray();
        $data['counting'] = [
            'requests' => $this->countRequests($model, $supervisor->id, SuperVisorRequests::PENDING, $model::IN_PROGRESS),
            'in_progress' => $this->countRequests($model, $supervisor->id, SuperVisorRequests::ACCEPTED, $model::IN_PROGRESS),
            'finished' => $this->countFinishedRequests($model, $supervisor->id),
        ];
        $data['rating'] = $this->averageRating($model, $supervisor->id);

        return Response::success($data);
    }

    /**
     * Update supervisor profile.
     */
    public function updateProfile(Request $request)
    {
        $supervisor = $request->user('supervisor');
        return (new SupervisorUpdatingService($supervisor))->update();
    }

    /**
     * List pending requests for supervisor.
     */
    public function requests()
    {
        $model = $this->getRequestModel();
        $data = $model::whereHas('supervisors', function ($q) {
            $q->where('status', SuperVisorRequests::PENDING)
                ->where('supervisor_id', auth('supervisor')->id());
        })->with(['unit', 'project', 'requester'])->get();

        return Response::success(['data' => $data]);
    }

    /**
     * List in-progress requests.
     */
    public function requestsInProgress()
    {
        $model = $this->getRequestModel();
        $data = $model::where('status', $model::IN_PROGRESS)
            ->whereHas('supervisors', function ($q) {
                $q->where('status', SuperVisorRequests::ACCEPTED)
                    ->where('supervisor_id', auth('supervisor')->id());
            })->with(['unit', 'project', 'requester'])->get();

        return Response::success(['data' => $data]);
    }

    /**
     * List finished requests.
     */
    public function requestsFinished()
    {
        $model = $this->getRequestModel();
        $data = $model::where('status', $model::FINISHED)
            ->whereHas('supervisors', function ($q) {
                $q->where('status', SuperVisorRequests::ACCEPTED)
                    ->where('supervisor_id', auth('supervisor')->id());
            })->with(['unit', 'project', 'requester'])->get();

        return Response::success(['data' => $data]);
    }

    /**
     * Accept or reject a request.
     */
    public function acceptOrReject(AcceptOrRejectRequestRequest $request, int $id)
    {
        $model = $this->getRequestModel();
        $object = $model::findOrFail($id);
        $supervisorId = auth('supervisor')->id();
        $status = $request->status === SuperVisorRequests::ACCEPTED
            ? SuperVisorRequests::ACCEPTED
            : SuperVisorRequests::REJECTED;

        DB::transaction(function () use ($object, $supervisorId, $status, $request) {
            $object->supervisors()->where('supervisor_id', $supervisorId)->update(['status' => $status]);
            $object->superVisorVisits()->create(['supervisor_id' => $supervisorId]);

            foreach ($request->attachments ?? [] as $attachment) {
                if ($attachment instanceof \Illuminate\Http\UploadedFile) {
                    $stored = StoragePictures::storeFile($attachment->path, $object);
                    if ($stored) {
                        $object->attachments()->create([
                            'path' => $stored,
                            'original_name' => $attachment->getClientOriginalName()
                        ]);
                    }
                }
            }
        });

        return Response::success([], ['تم استقبال ردك على الطلب']);
    }

    /**
     * Finish a request.
     */
    public function finishedRequest(FinishRequestRequest $request, int $id)
    {
        $model = $this->getRequestModel();
        $object = $model::findOrFail($id);

        if ($request->otp !== $object->otp) {
            return Response::error('رمز التحقق لا يطابق');
        }

        $object->update(['status' => $model::WAITING_RATING]);
        $object->load('supervisors');

        return (new SuperVisorUpdatingRequestService($object->supervisors))->update();
    }

    /**
     * Request another visit.
     */
    public function anotherVisit(AnotherVisitRequestRequest $request, int $id)
    {
        $model = $this->getRequestModel();
        $object = $model::findOrFail($id);

        DB::beginTransaction();

        try {
            $object->superVisorVisits()->create([
                'supervisor_id' => auth('supervisor')->id(),
                'reason' => $request->reason,
                'notes' => $request->notes,
            ]);

            $object->increment('visits_count');

            DB::commit();

            return Response::success([], ['تم ارسال طلب زيارة بنجاح']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return Response::error(['حدث خطأ أثناء إرسال طلب الزيارة.'], $e->getMessage());
        }
    }


    /**
     * Get current request model based on supervisor type.
     */
    protected function getRequestModel()
    {
        return auth('supervisor')->user()->type === 'planting' ? PlantingRequest::class : MaintenanceRequest::class;
    }

    /**
     * Count supervisor requests.
     */
    protected function countRequests($model, $supervisorId, $status, $progressStatus)
    {
        return $model::where('status', $progressStatus)
            ->whereHas('supervisors', function ($q) use ($status, $supervisorId) {
                $q->where('status', $status)->where('supervisor_id', $supervisorId);
            })->count();
    }

    /**
     * Count finished requests for supervisor.
     */
    protected function countFinishedRequests($model, $supervisorId)
    {
        return $model::where('status', $model::FINISHED)
            ->whereHas('supervisors', function ($q) use ($supervisorId) {
                $q->where('status', SuperVisorRequests::ACCEPTED)
                    ->where('supervisor_id', $supervisorId);
            })->count();
    }

    /**
     * Calculate average rating.
     */
    protected function averageRating($model, $supervisorId)
    {
        return $model::where('status', $model::FINISHED)
            ->whereHas('supervisors', function ($q) use ($supervisorId) {
                $q->where('status', SuperVisorRequests::ACCEPTED)
                    ->where('supervisor_id', $supervisorId);
            })->avg('rating') ?? 0;
    }
}
