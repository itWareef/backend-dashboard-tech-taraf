<?php

namespace App\Services\ProjectServices;

use App\Core\Classes\StoringData\AbstractClassHandleStoreData;

use App\Http\Requests\ProjectRequests\StoreProjectRequest;
use App\Models\Project\Project;

class ProjectStoringService extends AbstractClassHandleStoreData
{

    public function model(): string
    {
        return Project::class;
    }

    public function requestFile(): string
    {
       return StoreProjectRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم تسجيل  مشروع جديد";
    }
}
