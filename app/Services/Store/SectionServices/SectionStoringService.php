<?php

namespace App\Services\Store\SectionServices;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;

use App\Http\Requests\StoreSectionRequest;
use App\Models\Store\Section;

class SectionStoringService extends AbstractClassHandleStoreData
{

    public function model(): string
    {
        return Section::class;
    }

    public function requestFile(): string
    {
       return StoreSectionRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم تسجيل  بند جديد";
    }
}
