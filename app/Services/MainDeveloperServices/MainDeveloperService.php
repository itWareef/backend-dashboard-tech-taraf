<?php

namespace App\Services\MainDeveloperServices;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;
use App\Models\MainDeveloper;

class MainDeveloperService extends AbstractClassHandleStoreData
{
    public function model(): string
    {
        return MainDeveloper::class;
    }

    public function requestFile(): string
    {
        return \App\Http\Requests\MainDeveloperRequests\StoreMainDeveloperRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم إضافة المطور الرئيسي بنجاح";
    }

    protected function getDataHandle(): array
    {
        $data = parent::getDataHandle();
        // Add any additional data processing here if needed
        return $data;
    }
} 