<?php

namespace App\Services\Store\AdvertisingPostServices;

use App\Core\Classes\UpdatingData\AbstractClassHandleUpdate;
use App\Http\Requests\UpdateAdvertisingPostRequest;


class AdvertisingPostUpdatingService extends AbstractClassHandleUpdate
{

    public function messageSuccessAction(): string
    {
        return 'تم تعديل منشور بنجاح';
    }

    function requestFile(): string
    {
        return UpdateAdvertisingPostRequest::class;
    }
}
