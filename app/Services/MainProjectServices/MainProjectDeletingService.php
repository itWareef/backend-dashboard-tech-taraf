<?php

namespace App\Services\MainProjectServices;

use App\Core\Classes\DeletingData\AbstractClassHandleDelete;

class MainProjectDeletingService extends AbstractClassHandleDelete
{
    public function messageSuccessAction(): string
    {
        return 'تم حذف المشروع الرئيسي بنجاح';
    }
} 