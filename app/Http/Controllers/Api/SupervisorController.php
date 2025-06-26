<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupervisorRequests\SupervisorLoginRequest;
use App\Http\Requests\VerifyOtpRequest;

use App\Models\Supervisor;
use App\Services\SupervisorServices\SupervisorAuthService;

use App\Services\SupervisorServices\SupervisorUpdatingService;
use App\Services\SupervisorServices\RegisterSupervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Rap2hpoutre\FastExcel\FastExcel;
use Spatie\QueryBuilder\QueryBuilder;

class SupervisorController extends Controller
{

    public function register()
    {
        return (new RegisterSupervisor())->storeNewRecord() ;
    }
    public function delete(Supervisor $supervisor)
    {
        return (new SupervisorDeletingService($supervisor))->delete() ;
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
    public function export()
    {
        return (new SupervisorExportService())->export();
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
        $request->user('Supervisor')->token()->revoke();
        return Response::success([], ['Successfully logged out'] , 200);
    }
    public function me(Request $request)
    {
        $supervisor = $request->user('Supervisor');
        return Response::success($supervisor->toArray());
    }
    public function updateProfile(Request $request)
    {
        $supervisor = $request->user('Supervisor');
        return (new SupervisorUpdatingService($supervisor))->update() ;
    }
    public function addAdress(){
        return (new AddNewAddressToSupervisor())->storeNewRecord();
    }
    public function allAdress(){
        $data = QueryBuilder::for(SupervisorAddress::class)
        ->where('customer_id', auth('Supervisor')->user()->id)
        ->with(['city','country'])
        ->get()
        ->toArray();
        return Response::success($data);
    }
}
