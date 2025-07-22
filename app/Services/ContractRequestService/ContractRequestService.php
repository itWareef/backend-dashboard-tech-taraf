<?php

namespace App\Services\ContractRequestService;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;
use App\Http\Requests\ContractRequest\StoreContractRequest;
use App\Models\RequestMaintenanceAndService\ContractRequest;

class ContractRequestService extends AbstractClassHandleStoreData
{
    private string $otp='';

    protected function getDataHandle(): array
    {
        $number = rand(1000, 9999);
        $this->otp = $number;
        return array_merge(parent::getDataHandle() ,['request_number' => $number ,'customer_id' => auth('customer')->id()]);
    }
    public function model(): string
    {
        return ContractRequest::class;
    }

    public function requestFile(): string
    {
       return StoreContractRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم تسجيل طلب عقد جديد";
    }
}
