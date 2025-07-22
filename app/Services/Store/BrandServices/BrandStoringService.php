<?php

namespace App\Services\Store\BrandServices;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;
use App\Http\Requests\StoreBrandRequest;

use App\Models\Store\Brand;

class BrandStoringService extends AbstractClassHandleStoreData
{

    public function model(): string
    {
        return Brand::class;
    }

    public function requestFile(): string
    {
       return StoreBrandRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم تسجيل  صنف جديد";
    }
}
