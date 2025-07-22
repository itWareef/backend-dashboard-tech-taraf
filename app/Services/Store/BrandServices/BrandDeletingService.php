<?php

namespace App\Services\Store\BrandServices;

use App\Core\Classes\DeletingData\AbstractClassHandleDelete;


class BrandDeletingService extends AbstractClassHandleDelete
{

    public function messageSuccessAction(): string
    {
        return 'تم حذف صنف بنجاح';
    }
}
