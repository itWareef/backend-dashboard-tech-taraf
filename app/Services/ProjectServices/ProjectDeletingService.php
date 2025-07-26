<?php

namespace App\Services\ProjectServices;

use App\Core\Classes\DeletingData\AbstractClassHandleDelete;


class ProjectDeletingService extends AbstractClassHandleDelete
{

    public function messageSuccessAction(): string
    {
        return 'تم حذف مشروع بنجاح';
    }
}
