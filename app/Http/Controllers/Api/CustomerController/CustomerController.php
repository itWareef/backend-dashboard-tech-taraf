<?php

namespace App\Http\Controllers\Api\CustomerController;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequests\CustomerLoginRequest;
use App\Http\Requests\VendorRequests\VendorAdminLoginRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Models\AuthenticationModule\Customer\CustomerAddress;
use App\Models\Customer\Customer;
use App\Services\AddNewAddressToCustomer;
use App\Services\CustomerServices\CustomerAuthService;
use App\Services\CustomerServices\CustomerDeletingService;
use App\Services\CustomerServices\CustomerExportService;
use App\Services\CustomerServices\CustomerStatistics;
use App\Services\CustomerServices\CustomerUpdatingService;
use App\Services\CustomerServices\RegisterCustomer;
use App\Services\VendorServices\VendorAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Rap2hpoutre\FastExcel\FastExcel;
use Spatie\QueryBuilder\QueryBuilder;

class CustomerController extends Controller
{

    public function register()
    {
        dd(request()->all());
        return (new RegisterCustomer())->storeNewRecord() ;
    }
    public function delete(Customer $customer)
    {
        return (new CustomerDeletingService($customer))->delete() ;
    }
    public function update(Customer $customer)
    {
        return (new CustomerUpdatingService($customer))->update() ;
    }
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Customer::class)->allowedFilters([
            'first_name',
            'last_name',
            'name',
            'username',
            'email',
            'phone',
            'date_of_birth',
            'expire_identify',
        ])->datesFiltering()->paginate(7);
        $statistics = (new CustomerStatistics())->getStatistics();
        $total = Customer::count();
        return \response()->json(['list' =>$data ,'statistics' =>$statistics ,'total' =>$total]);
    }

    public function show(Request $request ,Customer $customer)
    {
        $data = $customer->toArray();
        return Response::success($data);
    }
    public function list(Request $request)
    {
        $data = QueryBuilder::for(Customer::class)->allowedFilters([
            'name',
        ])->get(['id' ,'name','picture'])->toArray();
        return Response::success($data);
    }
    public function export()
    {
        return (new CustomerExportService())->export();
    }
    public function login(CustomerLoginRequest $request)
    {
        return (new CustomerAuthService())->login($request);
    }
    public function verifyOtp(VerifyOtpRequest $request)
    {
        return (new CustomerAuthService())->verifyOtp($request);
    }
    public function logout(Request $request)
    {
        $request->user('customer')->token()->revoke();
        return Response::success([], ['Successfully logged out'] , 200);
    }
    public function me(Request $request)
    {
        $customer = $request->user('customer');
        return Response::success($customer->toArray());
    }
    public function updateProfile(Request $request)
    {
        $customer = $request->user('customer');
        return (new CustomerUpdatingService($customer))->update() ;
    }
    public function addAdress(){
        return (new AddNewAddressToCustomer())->storeNewRecord();
    }
    public function allAdress(){
        $data = QueryBuilder::for(CustomerAddress::class)
        ->where('customer_id', auth('customer')->user()->id)
        ->with(['city','country'])
        ->get()
        ->toArray();
        return Response::success($data);
    }
}
