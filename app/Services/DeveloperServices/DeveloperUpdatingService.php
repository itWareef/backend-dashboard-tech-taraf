<?php

namespace App\Services\DeveloperServices;

use App\Core\Classes\UpdatingData\AbstractClassHandleUpdate;
use App\Http\Requests\DeveloperRequests\UpdateDeveloperRequest;
use App\Http\Requests\UpdateBrandRequest;


class DeveloperUpdatingService extends AbstractClassHandleUpdate
{

    public function messageSuccessAction(): string
    {
        return 'تم تعديل مطور بنجاح';
    }

    function requestFile(): string
    {
        return UpdateDeveloperRequest::class;
    }
}
