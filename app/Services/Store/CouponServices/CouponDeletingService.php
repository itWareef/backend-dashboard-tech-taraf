<?php

namespace App\Services\Store\CouponServices;

use App\Core\Classes\DeletingData\AbstractClassHandleDelete;


class CouponDeletingService extends AbstractClassHandleDelete
{

    public function messageSuccessAction(): string
    {
        return 'تم حذف كوبون بنجاح';
    }
}
