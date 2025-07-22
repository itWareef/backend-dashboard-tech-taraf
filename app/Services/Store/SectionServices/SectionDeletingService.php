<?php

namespace App\Services\Store\SectionServices;

use App\Core\Classes\DeletingData\AbstractClassHandleDelete;


class SectionDeletingService extends AbstractClassHandleDelete
{

    public function messageSuccessAction(): string
    {
        return 'تم حذف بند بنجاح';
    }
}
