<?php

namespace App\Services\MainProjectServices;

use App\Core\Classes\UpdatingData\AbstractClassHandleUpdate;
use App\Http\Requests\MainProjectRequests\UpdateMainProjectRequest;

class MainProjectUpdatingService extends AbstractClassHandleUpdate
{
    public function messageSuccessAction(): string
    {
        return 'تم تعديل المشروع الرئيسي بنجاح';
    }

    function requestFile(): string
    {
        return UpdateMainProjectRequest::class;
    }
} 