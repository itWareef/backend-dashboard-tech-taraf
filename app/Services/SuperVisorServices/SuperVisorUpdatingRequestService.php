<?php

namespace App\Services\SuperVisorServices;

use App\Core\Classes\UpdatingData\AbstractClassHandleUpdate;
use App\Http\Requests\SupervisorRequests\SupervisorUpdatingRequest;
use App\Http\Requests\SupervisorRequests\UpdatingRequestRequest;


class SuperVisorUpdatingRequestService extends AbstractClassHandleUpdate
{

    public function messageSuccessAction(): string
    {
        return 'تم تعديل البيانات بنجاح';
    }

    function requestFile(): string
    {
        return UpdatingRequestRequest::class;
    }
}
