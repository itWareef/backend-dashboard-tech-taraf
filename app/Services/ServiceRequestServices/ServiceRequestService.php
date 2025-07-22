<?php

namespace App\Services\ServiceRequestServices;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;
use App\Http\Requests\StoreMaintenanceRequest;
use App\Models\RequestMaintenanceAndService\ServiceRequest;

class ServiceRequestService extends AbstractClassHandleStoreData
{

    protected function getDataHandle(): array
    {
        return array_merge(parent::getDataHandle() ,['requester_id' => auth('customer')->id()]);
    }

    public function model(): string
    {
        return ServiceRequest::class;
    }

    public function requestFile(): string
    {
       return StoreMaintenanceRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم تسجيل طلب صيانة جديد";
    }
}
