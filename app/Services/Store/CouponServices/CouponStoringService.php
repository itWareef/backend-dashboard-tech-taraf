<?php

namespace App\Services\Store\CouponServices;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;

use App\Http\Requests\StoreCouponRequest;
use App\Models\Store\Coupon;

class CouponStoringService extends AbstractClassHandleStoreData
{

    public function model(): string
    {
        return Coupon::class;
    }

    public function requestFile(): string
    {
       return StoreCouponRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم تسجيل كوبون جديد";
    }
}
