<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Listeners\CreateInvoiceForOrder;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Passport::ignoreRoutes();

        // Register event listeners
        $this->app['events']->listen(
            OrderCreated::class,
            CreateInvoiceForOrder::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        collect(glob(__DIR__ . '/../Macros/*.php'))
            ->each(function ($path) {
                require $path;
            });
        Passport::tokensExpireIn(Carbon::now()->addDays(15));

        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        Passport::tokensCan([
            'customer' => 'Access customer Backend',
            'supervisor' => 'Access supervisor Backend',
            'api' => 'Access API Backend',
        ]);
        Passport::setDefaultScope([
            'supervisor',
            'customer',
            'api'
        ]);
    }
}
