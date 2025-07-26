<?php

namespace App\Services\SupplierServices;

use App\Core\Classes\DeletingData\AbstractClassHandleDelete;


class SupplierDeletingService extends AbstractClassHandleDelete
{

    public function messageSuccessAction(): string
    {
        return 'تم حذف مورد بنجاح';
    }
}
