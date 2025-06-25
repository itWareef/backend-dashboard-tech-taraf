<?php

namespace App\Services\CustomerServices;

use App\Core\Classes\DeletingData\AbstractClassHandleDelete;


class CustomerDeletingService extends AbstractClassHandleDelete
{

    public function messageSuccessAction(): string
    {
        return 'Customer Deleted Successfully';
    }
}
