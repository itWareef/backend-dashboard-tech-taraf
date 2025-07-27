<?php

namespace App\Services\MainDeveloperServices;

use App\Core\Classes\UpdatingData\AbstractClassHandleUpdate;
use App\Http\Requests\MainDeveloperRequests\UpdateMainDeveloperRequest;

class MainDeveloperUpdatingService extends AbstractClassHandleUpdate
{
    public function messageSuccessAction(): string
    {
        return 'تم تعديل المطور الرئيسي بنجاح';
    }

    function requestFile(): string
    {
        return UpdateMainDeveloperRequest::class;
    }
} 