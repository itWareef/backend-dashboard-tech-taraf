<?php

namespace App\Services\SuperVisorServices;

use App\Core\Classes\UpdatingData\AbstractClassHandleUpdate;
use App\Http\Requests\SupervisorRequests\SupervisorUpdatingRequest;


class SuperVisorUpdatingService extends AbstractClassHandleUpdate
{

    public function messageSuccessAction(): string
    {
        return 'تم تعديل البيانات بنجاح';
    }

    function requestFile(): string
    {
        return SupervisorUpdatingRequest::class;
    }
    protected function getDataHandle(): array
    {
        $data = parent::getDataHandle();

        if (!empty($data['first_name']) && !empty($data['last_name'])) {
            $data['name'] = $data['first_name'] . ' ' . $data['last_name'];
        } else {
            $data['name'] = $data['first_name'] ?? $data['last_name'] ?? '';
        }

        return $data;
    }

}
