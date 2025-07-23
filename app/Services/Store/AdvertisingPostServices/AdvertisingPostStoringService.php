<?php

namespace App\Services\Store\AdvertisingPostServices;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;
use App\Http\Requests\StoreAdvertisingPostRequest;

use App\Models\Store\AdvertisingPost;

class AdvertisingPostStoringService extends AbstractClassHandleStoreData
{

    public function model(): string
    {
        return AdvertisingPost::class;
    }

    public function requestFile(): string
    {
       return StoreAdvertisingPostRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم تسجيل  منشور جديد";
    }
}
