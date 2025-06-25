<?php

namespace App\Services\CustomerServices;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;
use App\Http\Requests\CustomerRequests\RegisterCustomerRequest;
use App\Models\Customer\Customer;
use Illuminate\Support\Facades\Hash;

class RegisterCustomer extends AbstractClassHandleStoreData
{

    public function model(): string
    {
        return Customer::class;
    }

    public function requestFile(): string
    {
       return RegisterCustomerRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return " new Customer Register successfully! ..... Check Your Mail for Verify";
    }

    protected function getDataHandle(): array
    {
        $data = parent::getDataHandle();
        $data['password'] = Hash::make($data['password']);
        $data['name'] = $data['first_name'].' '.$data['last_name'];
        return $data;
    }
}
