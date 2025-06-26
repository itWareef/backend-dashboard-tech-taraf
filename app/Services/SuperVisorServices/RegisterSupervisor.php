<?php

namespace App\Services\SuperVisorServices;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;
use App\Http\Requests\CustomerRequests\RegisterCustomerRequest;
use App\Models\Customer\Customer;
use App\Models\Supervisor;
use Illuminate\Support\Facades\Hash;

class RegisterSupervisor extends AbstractClassHandleStoreData
{

    public function model(): string
    {
        return Supervisor::class;
    }

    public function requestFile(): string
    {
       return RegisterCustomerRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم تسجيل مستخدم جديد";
    }

    protected function getDataHandle(): array
    {
        $data = parent::getDataHandle();
        $data['password'] = Hash::make($data['password']);
        $data['name'] = $data['first_name'].' '.$data['last_name'];
        return $data;
    }
}
