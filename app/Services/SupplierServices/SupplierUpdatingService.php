<?php

namespace App\Services\SupplierServices;

use App\Core\Classes\UpdatingData\AbstractClassHandleUpdate;
use App\Http\Requests\SupplierRequests\UpdateSupplierRequest;


class SupplierUpdatingService extends AbstractClassHandleUpdate
{

    public function messageSuccessAction(): string
    {
        return 'تم تعديل مورد بنجاح';
    }

    function requestFile(): string
    {
        return UpdateSupplierRequest::class;
    }
}
