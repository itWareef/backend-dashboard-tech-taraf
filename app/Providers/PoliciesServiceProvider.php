<?php

namespace App\Providers;



use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Laravel\Passport\Passport;

class PoliciesServiceProvider extends AuthServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        Passport::ignoreRoutes();
    }


    public function boot()
    {
//        $this->policies = config('policies');
//        $this->registerPolicies();

    }
}
