<?php

namespace App\Services\MainDeveloperServices;

use App\Core\Classes\DeletingData\AbstractClassHandleDelete;

class MainDeveloperDeletingService extends AbstractClassHandleDelete
{
    public function messageSuccessAction(): string
    {
        return 'تم حذف المطور الرئيسي بنجاح';
    }
} 