<?php

namespace App\Services\Store\BrandServices;

use App\Core\Classes\UpdatingData\AbstractClassHandleUpdate;
use App\Http\Requests\UpdateBrandRequest;


class BrandUpdatingService extends AbstractClassHandleUpdate
{

    public function messageSuccessAction(): string
    {
        return 'تم تعديل صنف بنجاح';
    }

    function requestFile(): string
    {
        return UpdateBrandRequest::class;
    }
}
