<?php

namespace App\Services\CustomerServices;

use App\Core\Classes\UpdatingData\AbstractClassHandleUpdate;
use App\Http\Requests\CustomerRequests\CustomerUpdatingRequest;
use Illuminate\Support\Facades\Hash;


class CustomerUpdatingService extends AbstractClassHandleUpdate
{

    public function messageSuccessAction(): string
    {
        return 'Customer Updated Successfully';
    }

    function requestFile(): string
    {
        return CustomerUpdatingRequest::class;
    }
    protected function getDataHandle(): array
    {
        $data = parent::getDataHandle();
        $data['name'] = $data['first_name'].' '.$data['last_name'];
        return $data;
    }
}
