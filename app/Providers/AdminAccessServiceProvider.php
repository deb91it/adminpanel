<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//use App\Repositories\Admin\Notification\NotificationRepository;

class AdminAccessServiceProvider extends ServiceProvider
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
        //Admin
        $this->app->bind(
            'App\Repositories\Admin\AdminUser\AdminUserRepository',
            'App\Repositories\Admin\AdminUser\EloquentAdminUserRepository'
        );
        $this->app->bind(
            'App\Repositories\Admin\User\UserRepository',
            'App\Repositories\Admin\User\EloquentUserRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Role\RoleRepository',
            'App\Repositories\Admin\Role\EloquentRoleRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\PermissionGroup\PermissionGroupRepository',
            'App\Repositories\Admin\PermissionGroup\EloquentPermissionGroupRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Permission\PermissionRepository',
            'App\Repositories\Admin\Permission\EloquentPermissionRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Role\RoleRepository',
            'App\Repositories\Admin\Role\EloquentRoleRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Menu\MenuRepository',
            'App\Repositories\Admin\Menu\EloquentMenuRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Member\MemberRepository',
            'App\Repositories\Admin\Member\EloquentMemberRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Passenger\PassengerRepository',
            'App\Repositories\Admin\Passenger\EloquentPassengerRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Driver\DriverRepository',
            'App\Repositories\Admin\Driver\EloquentDriverRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Merchant\MerchantRepository',
            'App\Repositories\Admin\Merchant\EloquentMerchantRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Address\AddressRepository',
            'App\Repositories\Admin\Address\EloquentAddressRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\CommonTask\CommonTaskRepository',
            'App\Repositories\Admin\CommonTask\EloquentCommonTaskRepository'
        );

//        $this->app->bind(
//            'App\Repositories\Admin\Notification\NotificationRepository',
//            'App\Repositories\Admin\Notification\EloquentNotificationRepository'
//        );

        $this->app->bind(
            'App\Repositories\Admin\Dashboard\DashboardRepository',
            'App\Repositories\Admin\Dashboard\EloquentDashboardRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Approve\ApproveRepository',
            'App\Repositories\Admin\Approve\EloquentApproveRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\VehicleType\VehicleTypeRepository',
            'App\Repositories\Admin\VehicleType\EloquentVehicleTypeRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Vehicle\VehicleRepository',
            'App\Repositories\Admin\Vehicle\EloquentVehicleRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Setting\SettingRepository',
            'App\Repositories\Admin\Setting\EloquentSettingRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\PushNotification\PushNotificationRepository',
            'App\Repositories\Admin\PushNotification\EloquentPushNotificationRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\PaymentMethod\PaymentMethodRepository',
            'App\Repositories\Admin\PaymentMethod\EloquentPaymentMethodRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Trip\TripRepository',
            'App\Repositories\Admin\Trip\EloquentTripRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\PromoCode\PromoCodeRepository',
            'App\Repositories\Admin\PromoCode\EloquentPromoCodeRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Report\ReportRepository',
            'App\Repositories\Admin\Report\EloquentReportRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\DriverReport\DriverReportRepository',
            'App\Repositories\Admin\DriverReport\EloquentDriverReportRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\PassengerReport\PassengerReportRepository',
            'App\Repositories\Admin\PassengerReport\EloquentPassengerReportRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\WeekSettle\WeekSettleRepository',
            'App\Repositories\Admin\WeekSettle\EloquentWeekSettleRepository'
        );
        
        $this->app->bind(
            'App\Repositories\Admin\Accounts\AccountsRepository',
            'App\Repositories\Admin\Accounts\EloquentAccountsRepository'
        );
        
        $this->app->bind(
            'App\Repositories\Admin\MerchantReport\MerchantReportRepository',
            'App\Repositories\Admin\MerchantReport\EloquentMerchantReportRepository'
        );
        
        $this->app->bind(
            'App\Repositories\Admin\Emap\EmapRepository',
            'App\Repositories\Admin\Emap\EloquentEmapRepository'
        );
        
        $this->app->bind(
            'App\Repositories\Admin\Quest\QuestRepository',
            'App\Repositories\Admin\Quest\EloquentQuestRepository'
        );
        
        $this->app->bind(
            'App\Repositories\Admin\VehicleMake\VehicleMakeRepository',
            'App\Repositories\Admin\VehicleMake\EloquentVehicleMakeRepository'
        );
        
        $this->app->bind(
            'App\Repositories\Admin\VehicleModel\VehicleModelRepository',
            'App\Repositories\Admin\VehicleModel\EloquentVehicleModelRepository'
        );
        
        $this->app->bind(
            'App\Repositories\Admin\VehicleRegistrationCity\VehicleRegistrationCityRepository',
            'App\Repositories\Admin\VehicleRegistrationCity\EloquentVehicleRegistrationCityRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Agent\AgentRepository',
            'App\Repositories\Admin\Agent\EloquentAgentRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Company\CompanyRepository',
            'App\Repositories\Admin\Company\EloquentCompanyRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\BookingTripRequest\BookingTripRequestRepository',
            'App\Repositories\Admin\BookingTripRequest\EloquentBookingTripRequestRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\FcmNotification\FcmNotificationRepository',
            'App\Repositories\Admin\FcmNotification\EloquentFcmNotificationRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Feedback\FeedbackRepository',
            'App\Repositories\Admin\Feedback\EloquentFeedbackRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\AgentAccounts\AgentAccountsRepository',
            'App\Repositories\Admin\AgentAccounts\EloquentAgentAccountsRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\LocationPin\LocationPinRepository',
            'App\Repositories\Admin\LocationPin\EloquentLocationPinRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\CustomerQuery\CustomerQueryRepository',
            'App\Repositories\Admin\CustomerQuery\EloquentCustomerQueryRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\PartnerCompanies\PartnerCompaniesRepository',
            'App\Repositories\Admin\PartnerCompanies\EloquentPartnerCompaniesRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\PartnerCategory\PartnerCategoryRepository',
            'App\Repositories\Admin\PartnerCategory\EloquentPartnerCategoryRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\SystemSettings\SystemSettingsRepository',
            'App\Repositories\Admin\SystemSettings\EloquentSystemSettingsRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Merchant\MerchantRepository',
            'App\Repositories\Admin\Merchant\EloquentMerchantRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Plans\PlansRepository',
            'App\Repositories\Admin\Plans\EloquentPlansRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\PlansAssign\PlansAssignRepository',
            'App\Repositories\Admin\PlansAssign\EloquentPlansAssignRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\CourierZones\CourierZonesRepository',
            'App\Repositories\Admin\CourierZones\EloquentCourierZonesRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Category\CategoryRepository',
            'App\Repositories\Admin\Category\EloquentCategoryRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Delivery\DeliveryRepository',
            'App\Repositories\Admin\Delivery\EloquentDeliveryRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Collected\CollectedRepository',
            'App\Repositories\Admin\Collected\EloquentCollectedRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Stores\StoresRepository',
            'App\Repositories\Admin\Stores\EloquentStoresRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Product\ProductRepository',
            'App\Repositories\Admin\Product\EloquentProductRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Invoice\InvoiceRepository',
            'App\Repositories\Admin\Invoice\EloquentInvoiceRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Hub\HubRepository',
            'App\Repositories\Admin\Hub\EloquentHubRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Notification\NotificationRepository',
            'App\Repositories\Admin\Notification\EloquentNotificationRepository'
        );

        $this->app->bind(
            'App\Repositories\Admin\Expense\ExpenseRepository',
            'App\Repositories\Admin\Expense\EloquentExpenseRepository'
        );
        $this->app->bind(
            'App\Repositories\Admin\FinancialStatement\FinancialStatementRepository',
            'App\Repositories\Admin\FinancialStatement\EloquentFinancialStatementRepository'
        );
        $this->app->bind(
            'App\Repositories\Admin\FCMNotification\FCMNotificationRepository',
            'App\Repositories\Admin\FCMNotification\EloquentFCMNotificationRepository'
        );
    }
}
