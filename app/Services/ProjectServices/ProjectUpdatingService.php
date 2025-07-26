<?php

namespace App\Services\ProjectServices;

use App\Core\Classes\UpdatingData\AbstractClassHandleUpdate;
use App\Http\Requests\GuaranteeRequests\UpdateGuaranteeRequest;
use App\Http\Requests\ProjectRequests\UpdateProjectRequest;
use App\Http\Requests\SupplierRequests\UpdateSupplierRequest;


class ProjectUpdatingService extends AbstractClassHandleUpdate
{

    public function messageSuccessAction(): string
    {
        return 'تم تعديل مشروع بنجاح';
    }

    function requestFile(): string
    {
        return UpdateProjectRequest::class;
    }
}
