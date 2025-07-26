<?php

namespace App\Services\GuaranteeServices;

use App\Core\Classes\UpdatingData\AbstractClassHandleUpdate;
use App\Http\Requests\GuaranteeRequests\UpdateGuaranteeRequest;
use App\Http\Requests\SupplierRequests\UpdateSupplierRequest;


class GuaranteeUpdatingService extends AbstractClassHandleUpdate
{

    public function messageSuccessAction(): string
    {
        return 'تم تعديل ضمان بنجاح';
    }

    function requestFile(): string
    {
        return UpdateGuaranteeRequest::class;
    }
}
