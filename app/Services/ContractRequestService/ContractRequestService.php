<?php

namespace App\Services\ContractRequestService;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;
use App\Http\Requests\ContractRequest\StoreContractRequest;
use App\Models\RequestMaintenanceAndService\ContractRequest;

class ContractRequestService extends AbstractClassHandleStoreData
{
    protected function getDataHandle(): array
    {
        return array_merge(parent::getDataHandle() ,['customer_id' => auth('customer')->id()]);
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
