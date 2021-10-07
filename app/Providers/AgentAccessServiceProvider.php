<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//use App\Repositories\Admin\Notification\NotificationRepository;

class AgentAccessServiceProvider extends ServiceProvider
{

    protected $notification;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
//    public function boot(NotificationRepository $notification)
//    {
//        $this->notification = $notification;
//        view()->share('notifications',  $this->notification->getNotificationPaginated(10));
//    }

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
            'App\Repositories\Agent\Dashboard\DashboardRepository',
            'App\Repositories\Agent\Dashboard\EloquentDashboardRepository'
        );
        $this->app->bind(
            'App\Repositories\Agent\Agent\AgentRepository',
            'App\Repositories\Agent\Agent\EloquentAgentRepository'
        );
        $this->app->bind(
            'App\Repositories\Agent\AttendanceReport\AttendanceReportRepository',
            'App\Repositories\Agent\AttendanceReport\EloquentAttendanceReportRepository'
        );
        $this->app->bind(
            'App\Repositories\Agent\OutletCategory\OutletCategoryRepository',
            'App\Repositories\Agent\OutletCategory\EloquentOutletCategoryRepository'
        );
        $this->app->bind(
            'App\Repositories\Agent\Outlet\OutletRepository',
            'App\Repositories\Agent\Outlet\EloquentOutletRepository'
        );
        $this->app->bind(
            'App\Repositories\Agent\AttendancePolicy\AttendancePolicyRepository',
            'App\Repositories\Agent\AttendancePolicy\EloquentAttendancePolicyRepository'
        );
        $this->app->bind(
            'App\Repositories\Agent\AttendancePolicyHead\AttendancePolicyHeadRepository',
            'App\Repositories\Agent\AttendancePolicyHead\EloquentAttendancePolicyHeadRepository'
        );
        $this->app->bind(
            'App\Repositories\Agent\AttendanceList\AttendanceListRepository',
            'App\Repositories\Agent\AttendanceList\EloquentAttendanceListRepository'
        );
        $this->app->bind(
            'App\Repositories\Agent\HolidaySettings\HolidaySettingsRepository',
            'App\Repositories\Agent\HolidaySettings\EloquentHolidaySettingsRepository'
        );
        $this->app->bind(
            'App\Repositories\Agent\Order\OrderRepository',
            'App\Repositories\Agent\Order\EloquentOrderRepository'
        );
        $this->app->bind(
            'App\Repositories\Agent\Zones\ZonesRepository',
            'App\Repositories\Agent\Zones\EloquentZonesRepository'
        );
    }
}
