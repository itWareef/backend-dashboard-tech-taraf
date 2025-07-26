<?php

namespace App\Services\DeveloperServices;

use App\Core\Classes\DeletingData\AbstractClassHandleDelete;


class DeveloperDeletingService extends AbstractClassHandleDelete
{

    public function messageSuccessAction(): string
    {
        return 'تم حذف مطور بنجاح';
    }
}
