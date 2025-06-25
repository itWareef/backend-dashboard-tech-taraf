<?php

namespace App\Services\CustomerServices;

use App\Jobs\AdminResetPasswordNotificationJob;
use App\Models\AdminPanel\Auth\Admin\Admin;
use App\Models\AdminPanel\Auth\Admin\AdminPasswordResetModel;
use App\Models\AuthenticationModule\Customer\Customer;
use App\Models\AuthenticationModule\Customer\PasswordResetCustomer;
use App\Models\Vendor\PasswordAdminVendorReset;
use App\Models\Vendor\VendorAdmin;
use App\Services\PasswordResetService;

class CustomerPasswordResetService extends PasswordResetService
{
    public function __construct()
    {
        parent::__construct(Customer::class, PasswordResetCustomer::class, 'api',);
    }


}
