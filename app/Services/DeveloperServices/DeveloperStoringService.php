<?php

namespace App\Services\DeveloperServices;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;
use App\Http\Requests\DeveloperRequests\StoreDeveloperRequest;

use App\Models\Developer;

class DeveloperStoringService extends AbstractClassHandleStoreData
{

    public function model(): string
    {
        return Developer::class;
    }

    public function requestFile(): string
    {
       return StoreDeveloperRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم تسجيل  مطور جديد";
    }
}
