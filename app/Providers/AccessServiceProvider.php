<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AccessServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAccess();
        $this->registerBindings();
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerAccess()
    {
        $this->app->bind('access', function($app) {
            return new Access($app);
        });
    }

    /**
     * Register service provider bindings
     */
    public function registerBindings() {

        $this->app->bind(
            'App\Repositories\Api\Member\MemberRepository',
            'App\Repositories\Api\Member\EloquentMemberRepository'
        );

        $this->app->bind(
            'App\Repositories\Api\Employee\EmployeeRepository',
            'App\Repositories\Api\Employee\EloquentEmployeeRepository'
        );

        $this->app->bind(
            'App\Repositories\Address\AddressRepository',
            'App\Repositories\Address\EloquentAddressRepository'
        );

        $this->app->bind(
            'App\Repositories\Api\CommonTask\CommonTaskRepository',
            'App\Repositories\Api\CommonTask\EloquentCommonTaskRepository'
        );

        $this->app->bind(
            'App\Repositories\Api\UtilsApi\UtilsApiRepository',
            'App\Repositories\Api\UtilsApi\EloquentUtilsApiRepository'
        );

        $this->app->bind(
            'App\Repositories\Api\Agent\AgentRepository',
            'App\Repositories\Api\Agent\EloquentAgentRepository'
        );

        $this->app->bind(
            'App\Repositories\Api\Dashboard\DashboardRepository',
            'App\Repositories\Api\Dashboard\EloquentDashboardRepository'
        );

        $this->app->bind(
            'App\Repositories\Api\Merchant\MerchantRepository',
            'App\Repositories\Api\Merchant\EloquentMerchantRepository'
        );

        $this->app->bind(
            'App\Repositories\Api\Store\StoreRepository',
            'App\Repositories\Api\Store\EloquentStoreRepository'
        );

        $this->app->bind(
            'App\Repositories\Api\Product\ProductRepository',
            'App\Repositories\Api\Product\EloquentProductRepository'
        );

        $this->app->bind(
            'App\Repositories\Api\Delivery\DeliveryRepository',
            'App\Repositories\Api\Delivery\EloquentDeliveryRepository'
        );

        $this->app->bind(
            'App\Repositories\Api\Invoice\InvoiceRepository',
            'App\Repositories\Api\Invoice\EloquentInvoiceRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\Rider\RiderRepository',
            'App\Repositories\Api\Rider\EloquentRiderRepository'
        );
        $this->app->bind(
            'App\Repositories\Api\Notification\NotificationRepository',
            'App\Repositories\Api\Notification\EloquentNotificationRepository'
        );
    }
}
