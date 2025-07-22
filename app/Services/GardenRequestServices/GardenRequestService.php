<?php

namespace App\Services\GardenRequestServices;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;
use App\Http\Requests\GardenRequestStoringRequest;
use App\Models\RequestMaintenanceAndService\GardenRequest;

class GardenRequestService extends AbstractClassHandleStoreData
{

    public function model(): string
    {
        return GardenRequest::class;
    }

    public function requestFile(): string
    {
       return GardenRequestStoringRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم تسجيل طلب خدمة جديد";
    }
    protected function getDataHandle(): array
    {
        $data = parent::getDataHandle();
        $data['customer_id'] = auth('customer')->id();
        return $data;
    }
}
