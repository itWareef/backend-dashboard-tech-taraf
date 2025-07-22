<?php

namespace App\Services\Store\FeatureServices;

use App\Core\Classes\UpdatingData\AbstractClassHandleUpdate;
use App\Http\Requests\UpdateFeatureRequest;


class FeatureUpdatingService extends AbstractClassHandleUpdate
{

    public function messageSuccessAction(): string
    {
        return 'تم تعديل ميزه بنجاح';
    }

    function requestFile(): string
    {
        return UpdateFeatureRequest::class;
    }
}
