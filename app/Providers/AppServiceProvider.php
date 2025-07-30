<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Listeners\CreateInvoiceForOrder;
use App\Models\RequestMaintenanceAndService\ContractRequest;
use App\Models\RequestMaintenanceAndService\GardenRequest;
use App\Models\RequestMaintenanceAndService\ServiceRequest;
use App\Models\RequestMaintenanceAndService\UnitRequest;
use App\Models\Requests\MaintenanceRequest;
use App\Models\Requests\PlantingRequest;
use App\Models\Store\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        Relation::enforceMorphMap([
            'order' => Order::class,
            'service_request' => ServiceRequest::class,
            'contract_request' => ContractRequest::class,
            'planting_request' => PlantingRequest::class,
            'unit_request' => UnitRequest::class,
            'maintenance_request' => MaintenanceRequest::class,
            'garden_request' => GardenRequest::class,
        ]);
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
