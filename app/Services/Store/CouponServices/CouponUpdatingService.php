<?php

namespace App\Services\Store\CouponServices;

use App\Core\Classes\UpdatingData\AbstractClassHandleUpdate;
use App\Http\Requests\UpdateCouponRequest;


class CouponUpdatingService extends AbstractClassHandleUpdate
{

    public function messageSuccessAction(): string
    {
        return 'تم تعديل كوبون بنجاح';
    }

    function requestFile(): string
    {
        return UpdateCouponRequest::class;
    }
}
