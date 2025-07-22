<?php

namespace App\Services\Store\SectionServices;

use App\Core\Classes\UpdatingData\AbstractClassHandleUpdate;
use App\Http\Requests\UpdateFeatureRequest;
use App\Http\Requests\UpdateSectionRequest;


class SectionUpdatingService extends AbstractClassHandleUpdate
{

    public function messageSuccessAction(): string
    {
        return 'تم تعديل بند بنجاح';
    }

    function requestFile(): string
    {
        return UpdateSectionRequest::class;
    }
}
