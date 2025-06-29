<?php

namespace App\Services\AdminServices;

use App\Core\Classes\UpdatingData\AbstractClassHandleUpdate;
use App\Http\Requests\AdminRequests\AdminUpdatingRequest;



class AdminUpdatingService extends AbstractClassHandleUpdate
{

    public function messageSuccessAction(): string
    {
        return 'تم تعديل البيانات بنجاح';
    }

    function requestFile(): string
    {
        return AdminUpdatingRequest::class;
    }
    protected function getDataHandle(): array
    {
        $data = parent::getDataHandle();
        $data['name'] = $data['first_name'].' '.$data['last_name'];
        return $data;
    }
}
