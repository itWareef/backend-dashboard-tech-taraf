<?php

namespace App\Services\MainProjectServices;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;
use App\Models\MainProject;

class MainProjectService extends AbstractClassHandleStoreData
{
    public function model(): string
    {
        return MainProject::class;
    }

    public function requestFile(): string
    {
        return \App\Http\Requests\MainProjectRequests\StoreMainProjectRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم إضافة المشروع الرئيسي بنجاح";
    }

} 