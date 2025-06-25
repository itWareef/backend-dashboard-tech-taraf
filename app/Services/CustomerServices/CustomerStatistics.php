<?php

namespace App\Services\CustomerServices;

use App\Core\Statistics\AbstractStatisticsRowsCounted;
use App\Models\AuthenticationModule\Customer\Customer;

class CustomerStatistics extends AbstractStatisticsRowsCounted
{

    protected function getModelQuery():string
    {
        return Customer::class;
    }
}
