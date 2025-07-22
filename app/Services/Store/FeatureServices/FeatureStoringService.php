<?php

namespace App\Services\Store\FeatureServices;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;

use App\Http\Requests\StoreFeatureRequest;
use App\Models\Store\Feature;

class FeatureStoringService extends AbstractClassHandleStoreData
{

    public function model(): string
    {
        return Feature::class;
    }

    public function requestFile(): string
    {
       return StoreFeatureRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم تسجيل ميزه جديد";
    }
}
