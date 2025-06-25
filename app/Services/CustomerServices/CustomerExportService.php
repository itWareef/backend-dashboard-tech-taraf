<?php

namespace App\Services\CustomerServices;

use App\Models\AuthenticationModule\Customer\Customer;
use App\Services\ExportServices;

class CustomerExportService extends ExportServices
{

    protected function modelClass(): string
    {
        return Customer::class;
    }

    protected function fileName(): string
    {
        return 'customers';
    }

    protected function headers(): array
    {
        return  [
            'ID',
            'First Name',
            'Last Name',
            'Name',
            'Email',
            'UserName',
            'Phone',
            'Date Of Birth',
            'Identify Expire Date'
        ];
    }

    protected function rowData(): array
    {
       return   [
           'id',
           'first_name',
           'last_name',
           'name',
           'email',
           'username',
           'phone',
           'date_of_birth',
           'expire_identify',
       ];
    }
    protected function allowedFilters(): array
    {
        return [
            'first_name',
            'last_name',
            'name',
            'username',
            'email',
            'phone',
            'date_of_birth',
            'expire_identify',
            ];
    }
}
