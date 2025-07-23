<?php

namespace App\Services\Store\AdvertisingPostServices;

use App\Core\Classes\DeletingData\AbstractClassHandleDelete;


class AdvertisingPostDeletingService extends AbstractClassHandleDelete
{

    public function messageSuccessAction(): string
    {
        return 'تم حذف منشور بنجاح';
    }
}
