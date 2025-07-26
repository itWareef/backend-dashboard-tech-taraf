<?php

namespace App\Services\GuaranteeServices;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;

use App\Http\Requests\GuaranteeRequests\StoreGuaranteeRequest;
use App\Models\Guarantee;

class GuaranteeStoringService extends AbstractClassHandleStoreData
{

    public function model(): string
    {
        return Guarantee::class;
    }

    public function requestFile(): string
    {
       return StoreGuaranteeRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم تسجيل  ضمان جديد";
    }
}
