<?php

namespace App\Services\SupplierServices;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;

use App\Http\Requests\SupplierRequests\StoreSupplierRequest;
use App\Models\Supplier;

class SupplierStoringService extends AbstractClassHandleStoreData
{

    public function model(): string
    {
        return Supplier::class;
    }

    public function requestFile(): string
    {
       return StoreSupplierRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم تسجيل  مورد جديد";
    }
}
