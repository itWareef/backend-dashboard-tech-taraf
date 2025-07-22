<?php

namespace App\Services\UnitRequestServices;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;
use App\Http\Requests\StoreUnitRequest;
use App\Models\RequestMaintenanceAndService\UnitRequest;

class UnitRequestService extends AbstractClassHandleStoreData
{

    public function model(): string
    {
        return UnitRequest::class;
    }

    public function requestFile(): string
    {
       return StoreUnitRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم تسجيل طلب وحدة جديد";
    }
    protected function getDataHandle(): array
    {
        $data = parent::getDataHandle();
        $data['customer_id'] = auth('customer')->id();
        return $data;
    }
}
