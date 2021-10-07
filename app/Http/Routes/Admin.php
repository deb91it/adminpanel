<?php

/*Admin dashboard*/
Route::get('/', ['as' => 'admin.dashboard', 'middleware' => 'acl:dashboard', 'uses' => 'DashboardController@index']);
Route::get('today-trip-list', ['as' => 'admin.dashboard.today_trip_list', 'middleware' => 'acl:dashboard', 'uses' => 'DashboardController@getTodayTripList']);
Route::get('top-total-items', ['as' => 'admin.dashboard.top_total_items', 'middleware' => 'acl:dashboard', 'uses' => 'DashboardController@getTopTotalItems']);
Route::get('top-total-items-by-date', ['as' => 'admin.dashboard.top_total_items_by_date', 'middleware' => 'acl:dashboard', 'uses' => 'DashboardController@postTopTotalItemsByDateRange']);
Route::get('total-fare-collection', ['as' => 'admin.dashboard.total_fare_collection', 'middleware' => 'acl:dashboard', 'uses' => 'DashboardController@getTotalFareCollectionByDateRange']);

/*Admin Dashboard Statistics*/
Route::post('dashboard-operation-statistics', ['as' => 'admin.dashboard.data.operations', 'middleware' => 'acl:dashboard', 'uses' => 'DashboardController@getOperationStatistics']);
Route::post('dashboard-financial-statistics', ['as' => 'admin.dashboard.data.finance', 'middleware' => 'acl:dashboard', 'uses' => 'DashboardController@getFinancialStatistics']);
Route::post('dashboard-collection-statistics', ['as' => 'admin.dashboard.data.collection', 'middleware' => 'acl:dashboard', 'uses' => 'DashboardController@getCollectionStatistics']);
/*Admin Lock Screen*/
Route::get('lock-screen', ['as' => 'admin.dashboard.top_total_items', 'middleware' => 'acl:dashboard', 'uses' => 'DashboardController@lockScreen']);

// Admin User
Route::get('admin-users', ['middleware' => 'acl:view_admin_user', 'as' => 'admin.admin-users', 'uses' => 'AdminUserController@index']);
Route::get('admin-user/new', ['middleware' => 'acl:add_admin_user', 'as' => 'admin.admin-user.new', 'uses' => 'AdminUserController@create']);
Route::post('admin-user/store', ['middleware' => 'acl:add_admin_user', 'as' => 'admin.admin-user.store', 'uses' => 'AdminUserController@store']);
Route::get('admin-user/{id}/edit', ['middleware' => 'acl:edit_admin_user', 'as' => 'admin.admin-user.edit', 'uses' => 'AdminUserController@edit']);
Route::get('admin-user/{id}/change/password', ['middleware' => 'acl:edit_admin_user', 'as' => 'admin.admin-user.change.password', 'uses' => 'AdminUserController@changePasswordByAdmin']);
Route::post('admin-user/update/user-password', ['middleware' => 'acl:edit_admin_user', 'as' => 'admin.admin-user.update.user.password', 'uses' => 'AdminUserController@updatePasswordByAdmin']);
Route::post('admin-user/{id}/update', ['middleware' => 'acl:edit_admin_user', 'as' => 'admin.admin-user.update', 'uses' => 'AdminUserController@update']);
Route::get('admin-user/{id}/delete', ['middleware' => 'acl:delete_admin_user', 'as' => 'admin.admin-user.delete', 'uses' => 'AdminUserController@destroy']);
Route::get('change-password', ['middleware' => 'acl:view_dashboard', 'as' => 'admin.change_password', 'uses' => 'AdminUserController@changePassword']);
Route::post('change-password', ['middleware' => 'acl:view_dashboard', 'as' => 'admin.change_password', 'uses' => 'AdminUserController@postChangePassword']);
Route::get('edit-profile', ['middleware' => 'acl:view_dashboard', 'as' => 'admin.edit_profile', 'uses' => 'AdminUserController@getEditProfile']);

Route::group(['middleware' => ['afmid']], function () {
    Route::get('user-profile', ['middleware' => 'acl:view_dashboard', 'as' => 'admin.user_profile', 'uses' => 'AdminUserController@getViewProfile']);
});
Route::post('update-profile', ['middleware' => 'acl:view_dashboard', 'as' => 'admin.update_profile', 'uses' => 'AdminUserController@postEditProfile']);

// Agent
Route::get('agent', ['middleware' => 'acl:view_agent', 'as' => 'admin.agents', 'uses' => 'AgentController@index']);
Route::post('agent-export', ['middleware' => 'acl:view_agent', 'as' => 'admin.agents.export', 'uses' => 'AgentController@postExportFile']);
Route::get('agent_data', ['middleware' => 'acl:view_agent', 'as' => 'admin.datatable.agent', 'uses' => 'AgentController@getDataTableReport']);
Route::get('agent/new', ['middleware' => 'acl:add_agent', 'as' => 'admin.agent.new', 'uses' => 'AgentController@create']);
Route::post('agent/store', ['middleware' => 'acl:add_agent', 'as' => 'admin.agent.store', 'uses' => 'AgentController@store']);
Route::get('agent/{id}/edit', ['middleware' => 'acl:edit_agent', 'as' => 'admin.agent.edit', 'uses' => 'AgentController@edit']);
Route::post('agent/{id}/update', ['middleware' => 'acl:edit_agent', 'as' => 'admin.agent.update', 'uses' => 'AgentController@update']);
Route::get('agent/{id}/delete', ['middleware' => 'acl:delete_agent', 'as' => 'admin.agent.delete', 'uses' => 'AgentController@destroy']);
Route::get('rider-pickup/{id}/lists', ['middleware' => 'acl:view_pickup', 'as' => 'admin.agent.pickup.list', 'uses' => 'AgentController@getPickUpListsByRider']);
Route::get('rider_pickup_data', ['middleware' => 'acl:view_pickup', 'as' => 'admin.datatable.pickup.list', 'uses' => 'AgentController@getDataTablePickUpLists']);
Route::get('rider-delivery/{id}/lists', ['middleware' => 'acl:view_rider_delivery', 'as' => 'admin.agent.delivery.list', 'uses' => 'AgentController@getDeliveryListsByRider']);
Route::get('rider_delivery_data', ['middleware' => 'acl:view_rider_delivery', 'as' => 'admin.datatable.rider.delivery.list', 'uses' => 'AgentController@getDataTableDeliveryLists']);

// Company
Route::get('company', ['middleware' => 'acl:view_company', 'as' => 'admin.company', 'uses' => 'CompanyController@index']);
Route::post('company-export', ['middleware' => 'acl:view_company', 'as' => 'admin.company.export', 'uses' => 'CompanyController@postExportFile']);
Route::get('company_data', ['middleware' => 'acl:view_company', 'as' => 'admin.datatable.company', 'uses' => 'CompanyController@getDataTableReport']);
Route::get('company/new', ['middleware' => 'acl:add_company', 'as' => 'admin.company.new', 'uses' => 'CompanyController@create']);
Route::post('company/store', ['middleware' => 'acl:add_company', 'as' => 'admin.company.store', 'uses' => 'CompanyController@store']);
Route::get('company/{id}/edit', ['middleware' => 'acl:edit_company', 'as' => 'admin.company.edit', 'uses' => 'CompanyController@edit']);
Route::post('company/{id}/update', ['middleware' => 'acl:edit_company', 'as' => 'admin.company.update', 'uses' => 'CompanyController@update']);
Route::get('company/{id}/delete', ['middleware' => 'acl:delete_company', 'as' => 'admin.company.delete', 'uses' => 'CompanyController@destroy']);

// Common
Route::post('member/change_can_login', ['middleware' => 'acl:change_can_login_status', 'as' => 'admin.member.change_can_login', 'uses' => 'CommonTaskController@postChangeCanLoginStatus']);
Route::post('member/change_is_active', ['middleware' => 'acl:change_is_active_status', 'as' => 'admin.member.change_is_active', 'uses' => 'CommonTaskController@postChangeIsActiveStatus']);
Route::get('member/{id}/profile', ['middleware' => 'acl:execute_member', 'as' => 'admin.member.profile', 'uses' => 'CommonTaskController@profileData']);
Route::get('member/profile/{id}/download', ['middleware' => 'acl:execute_member', 'as' => 'admin.member.profile.image.download', 'uses' => 'CommonTaskController@profileImageDownload']);
Route::get('member/profile/profile_pdf', ['middleware' => 'acl:view_invoice', 'as' => 'admin.member.profile.pdf.download', 'uses' => 'CommonTaskController@profileDetailsPdf']);

// Permission Group
Route::resource('permission-group', 'PermissionGroupController', ['except' => ['store', 'update', 'destroy']]);
Route::post('permission-group/store', ['middleware' => 'acl:add_permission_group', 'as' => 'admin.permission-group.store', 'uses' => 'PermissionGroupController@store'])->where('id', '[0-9]+');
Route::post('permission-group/{id}/update', ['middleware' => 'acl:edit_permission_group', 'as' => 'admin.permission-group.update', 'uses' => 'PermissionGroupController@update'])->where('id', '[0-9]+');
Route::get('permission-group/{id}/delete', ['middleware' => 'acl:delete_permission_group', 'as' => 'admin.permission-group.delete', 'uses' => 'PermissionGroupController@destroy'])->where('id', '[0-9]+');

// Permission
Route::resource('permission', 'PermissionController', ['except' => ['store', 'update', 'destroy']]);
Route::get('permission-data', ['middleware' => 'acl:view_driver', 'as' => 'admin.datatable.permission', 'uses' => 'PermissionController@getDataTableReport']);
Route::post('permission/store', ['middleware' => 'acl:add_permission', 'as' => 'admin.permission.store', 'uses' => 'PermissionController@store'])->where('id', '[0-9]+');
Route::post('permission/{id}/update', ['middleware' => 'acl:edit_permission', 'as' => 'admin.permission.update', 'uses' => 'PermissionController@update'])->where('id', '[0-9]+');
Route::get('permission/{id}/delete', ['middleware' => 'acl:delete_permission', 'as' => 'admin.permission.delete', 'uses' => 'PermissionController@destroy'])->where('id', '[0-9]+');

// Roles
Route::get('role', ['middleware' => 'acl:view_role', 'as' => 'admin.roles', 'uses' => 'RoleController@index']);
Route::get('role/new', ['middleware' => 'acl:add_role', 'as' => 'admin.role.new', 'uses' => 'RoleController@create']);
Route::post('role/store', ['middleware' => 'acl:add_role', 'as' => 'admin.role.store', 'uses' => 'RoleController@store'])->where('id', '[0-9]+');
Route::get('role/{id}/edit', ['middleware' => 'acl:edit_role', 'as' => 'admin.role.edit', 'uses' => 'RoleController@edit'])->where('id', '[0-9]+');
Route::post('role/{id}/update', ['middleware' => 'acl:edit_role', 'as' => 'admin.role.update', 'uses' => 'RoleController@update'])->where('id', '[0-9]+');
Route::get('role/{id}/delete', ['middleware' => 'acl:delete_role', 'as' => 'admin.role.delete', 'uses' => 'RoleController@destroy'])->where('id', '[0-9]+');
Route::get('role/{id}/show', ['middleware' => 'acl:view_role', 'as' => 'admin.role.show', 'uses' => 'RoleController@show'])->where('id', '[0-9]+');

// Settings
Route::get('settings', ['middleware' => 'acl:view_settings', 'as' => 'admin.settings', 'uses' => 'SettingController@index']);
Route::post('settings/store', ['middleware' => 'acl:add_settings', 'as' => 'admin.settings.store', 'uses' => 'SettingController@store'])->where('id', '[0-9]+');

//System settings
Route::get('system-settings', ['middleware' => 'acl:view_partner_company', 'as' => 'admin.system-settings', 'uses' => 'SystemSettingsController@index']);
Route::get('system-settings/new', ['middleware' => 'acl:view_partner_company', 'as' => 'admin.system-settings.create', 'uses' => 'SystemSettingsController@create']);
Route::get('system-settings/edit/{company_id}', ['middleware' => 'acl:view_partner_company', 'as' => 'admin.system-settings.edit', 'uses' => 'SystemSettingsController@edit']);
Route::get('system-settings/details/{company_id}', ['middleware' => 'acl:view_partner_company', 'as' => 'admin.system-settings.details', 'uses' => 'SystemSettingsController@details']);
Route::post('system-settings/check', ['middleware' => 'acl:view_partner_company', 'as' => 'admin.system-settings.check', 'uses' => 'SystemSettingsController@check']);
Route::post('system-settings/store', ['middleware' => 'acl:view_partner_company', 'as' => 'admin.system-settings.store', 'uses' => 'SystemSettingsController@store']);
Route::post('system-settings/update', ['middleware' => 'acl:view_partner_company', 'as' => 'admin.system-settings.update', 'uses' => 'SystemSettingsController@update']);

// Get Zone by country id
Route::post('zone', ['as' => 'admin.passenger.zone', 'uses' => 'AgentController@getZoneByCountryId']);

// merchant
Route::get('merchant', ['middleware' => 'acl:view_merchant', 'as' => 'admin.merchants', 'uses' => 'MerchantController@index']);
Route::post('merchant-export', ['middleware' => 'acl:view_merchant', 'as' => 'admin.merchants.export', 'uses' => 'MerchantController@postExportFile']);
Route::get('merchant_data', ['middleware' => 'acl:view_merchant', 'as' => 'admin.datatable.merchant', 'uses' => 'MerchantController@getDataTableReport']);
Route::get('merchant/new', ['middleware' => 'acl:add_merchant', 'as' => 'admin.merchant.new', 'uses' => 'MerchantController@create']);
Route::post('merchant/save/info', ['as' => 'admin.merchant.store', 'uses' => 'MerchantController@store']);
Route::get('merchant/{id}/edit', ['middleware' => 'acl:edit_merchant', 'as' => 'admin.merchant.edit', 'uses' => 'MerchantController@edit']);
Route::post('merchant/{id}/update', ['middleware' => 'acl:edit_merchant', 'as' => 'admin.merchant.update', 'uses' => 'MerchantController@update']);
Route::get('merchant/{id}/inactive', ['middleware' => 'acl:delete_merchant', 'as' => 'admin.merchant.inactive', 'uses' => 'MerchantController@destroy']);
Route::get('merchant/{id}/delete', ['middleware' => 'acl:force_delete_merchant', 'as' => 'admin.merchant.delete', 'uses' => 'MerchantController@delete']);
Route::get('merchant/id_image/{id}/download', ['middleware' => 'acl:view_merchant', 'as' => 'admin.merchant.id.image.download', 'uses' => 'CommonTaskController@idTypeImageDownload']);
Route::post('merchant/approval', ['middleware' => 'acl:view_merchant', 'as' => 'admin.merchant.approve', 'uses' => 'MerchantController@merchantApproval']);
Route::post('merchant/approval/store', ['middleware' => 'acl:view_merchant', 'as' => 'admin.merchant.approve.store', 'uses' => 'MerchantController@merchantApprovalStore']);

// plan
Route::get('plan', ['middleware' => 'acl:view_plan', 'as' => 'admin.plans', 'uses' => 'PlansController@index']);
Route::post('plan-export', ['middleware' => 'acl:view_plan', 'as' => 'admin.plans.export', 'uses' => 'PlansController@postExportFile']);
Route::get('plan_data', ['middleware' => 'acl:view_plan', 'as' => 'admin.datatable.plan', 'uses' => 'PlansController@getDataTableReport']);
Route::get('plan/new', ['middleware' => 'acl:add_plan', 'as' => 'admin.plan.new', 'uses' => 'PlansController@create']);
Route::post('plan/store', ['middleware' => 'acl:add_plan', 'as' => 'admin.plan.store', 'uses' => 'PlansController@store']);
Route::get('plan/{id}/edit', ['middleware' => 'acl:edit_plan', 'as' => 'admin.plan.edit', 'uses' => 'PlansController@edit']);
Route::post('plan/{id}/update', ['middleware' => 'acl:edit_plan', 'as' => 'admin.plan.update', 'uses' => 'PlansController@update']);
Route::get('plan/{id}/delete', ['middleware' => 'acl:delete_plan', 'as' => 'admin.plan.delete', 'uses' => 'PlansController@destroy']);

// plan-assign
Route::get('plan-assign', ['middleware' => 'acl:view_plan-assign', 'as' => 'admin.plan-assigns', 'uses' => 'PlansAssignController@index']);
Route::post('plan-assign-export', ['middleware' => 'acl:view_plan-assign', 'as' => 'admin.plan-assigns.export', 'uses' => 'PlansAssignController@postExportFile']);
Route::get('plan-assign_data', ['middleware' => 'acl:view_plan-assign', 'as' => 'admin.datatable.plan-assign', 'uses' => 'PlansAssignController@getDataTableReport']);
Route::get('plan-assign/new', ['middleware' => 'acl:add_plan-assign', 'as' => 'admin.plan-assign.new', 'uses' => 'PlansAssignController@create']);
Route::post('plan-assign/store', ['middleware' => 'acl:add_plan-assign', 'as' => 'admin.plan-assign.store', 'uses' => 'PlansAssignController@store']);
Route::get('plan-assign/{id}/edit', ['middleware' => 'acl:edit_plan-assign', 'as' => 'admin.plan-assign.edit', 'uses' => 'PlansAssignController@edit']);
Route::post('plan-assign/{id}/update', ['middleware' => 'acl:edit_plan-assign', 'as' => 'admin.plan-assign.update', 'uses' => 'PlansAssignController@update']);
Route::get('plan-assign/{id}/delete', ['middleware' => 'acl:delete_plan-assign', 'as' => 'admin.plan-assign.delete', 'uses' => 'PlansAssignController@destroy']);
Route::post('plan-view', ['middleware' => 'acl:edit_plan-assign', 'as' => 'admin.plan-assign.view', 'uses' => 'PlansAssignController@viewPlans']);

// courier_zone
Route::get('courier-zone', ['middleware' => 'acl:view_courier_zone', 'as' => 'admin.courier_zones', 'uses' => 'CourierZonesController@index']);
Route::post('courier-zone-export', ['middleware' => 'acl:view_courier_zone', 'as' => 'admin.courier_zones.export', 'uses' => 'CourierZonesController@postExportFile']);
Route::get('courier-zone_data', ['middleware' => 'acl:view_courier_zone', 'as' => 'admin.datatable.courier_zone', 'uses' => 'CourierZonesController@getDataTableReport']);
Route::get('courier-zone/new', ['middleware' => 'acl:add_courier_zone', 'as' => 'admin.courier_zone.new', 'uses' => 'CourierZonesController@create']);
Route::post('courier-zone/store', ['middleware' => 'acl:add_courier_zone', 'as' => 'admin.courier_zone.store', 'uses' => 'CourierZonesController@store']);
Route::get('courier-zone/{id}/edit', ['middleware' => 'acl:edit_courier_zone', 'as' => 'admin.courier_zone.edit', 'uses' => 'CourierZonesController@edit']);
Route::post('courier-zone/{id}/update', ['middleware' => 'acl:edit_courier_zone', 'as' => 'admin.courier_zone.update', 'uses' => 'CourierZonesController@update']);
Route::get('courier-zone/{id}/delete', ['middleware' => 'acl:delete_courier_zone', 'as' => 'admin.courier_zone.delete', 'uses' => 'CourierZonesController@destroy']);

// category
Route::get('category', ['middleware' => 'acl:view_category', 'as' => 'admin.categorys', 'uses' => 'CategoryController@index']);
Route::post('category-export', ['middleware' => 'acl:view_category', 'as' => 'admin.categorys.export', 'uses' => 'CategoryController@postExportFile']);
Route::get('category_data', ['middleware' => 'acl:view_category', 'as' => 'admin.datatable.category', 'uses' => 'CategoryController@getDataTableReport']);
Route::get('category/new', ['middleware' => 'acl:add_category', 'as' => 'admin.category.new', 'uses' => 'CategoryController@create']);
Route::post('category/store', ['middleware' => 'acl:add_category', 'as' => 'admin.category.store', 'uses' => 'CategoryController@store']);
Route::get('category/{id}/edit', ['middleware' => 'acl:edit_category', 'as' => 'admin.category.edit', 'uses' => 'CategoryController@edit']);
Route::post('category/{id}/update', ['middleware' => 'acl:edit_category', 'as' => 'admin.category.update', 'uses' => 'CategoryController@update']);
Route::get('category/{id}/delete', ['middleware' => 'acl:delete_category', 'as' => 'admin.category.delete', 'uses' => 'CategoryController@destroy']);

// delivery
Route::get('delivery', ['middleware' => 'acl:view_delivery', 'as' => 'admin.deliverys', 'uses' => 'DeliveryController@index']);
Route::post('delivery-export', ['middleware' => 'acl:export_delivery', 'as' => 'admin.deliverys.export', 'uses' => 'DeliveryController@postExportFile']);
Route::get('delivery_data', ['middleware' => 'acl:view_delivery', 'as' => 'admin.datatable.delivery', 'uses' => 'DeliveryController@getDataTableReport']);
Route::post('delivery-products', ['middleware' => 'acl:view_delivery', 'as' => 'admin.delivery.product.view', 'uses' => 'DeliveryController@viewProducts']);
Route::post('products-approval', ['middleware' => 'acl:view_delivery', 'as' => 'admin.delivery.product.approval', 'uses' => 'DeliveryController@productsApproval']);
Route::get('delivery/new', ['middleware' => 'acl:add_delivery', 'as' => 'admin.deliveries.create', 'uses' => 'DeliveryController@create']);
Route::post('delivery/store', ['middleware' => 'acl:add_delivery', 'as' => 'admin.deliveries.store', 'uses' => 'DeliveryController@store']);
Route::get('delivery/{id}/edit', ['middleware' => 'acl:edit_delivery', 'as' => 'admin.delivery.edit', 'uses' => 'DeliveryController@edit']);
Route::get('delivery/{id}/delete', ['middleware' => 'acl:delete_delivery', 'as' => 'admin.delivery.delete', 'uses' => 'DeliveryController@delete']);
Route::post('delivery/{id}/update', ['middleware' => 'acl:edit_delivery', 'as' => 'admin.deliveries.update', 'uses' => 'DeliveryController@update']);
Route::post('products', ['middleware' => 'acl:view_delivery','as' => 'admin.delivery.products', 'uses' => 'DeliveryController@getProductByMerchantId']);
Route::post('stores', ['middleware' => 'acl:view_delivery','as' => 'admin.delivery.stores', 'uses' => 'DeliveryController@getStoreByMerchantId']);
Route::post('merchant-plans', ['middleware' => 'acl:view_delivery','as' => 'admin.delivery.merchants.plans', 'uses' => 'DeliveryController@getPlansByMerchantId']);
Route::post('returned-merchant-plans', ['middleware' => 'acl:view_delivery','as' => 'admin.delivery.merchants.return.plans', 'uses' => 'DeliveryController@getReturnPlansByMerchantId']);
Route::post('riders', ['middleware' => 'acl:view_delivery','as' => 'admin.delivery.riders', 'uses' => 'DeliveryController@getRiders']);
Route::post('hub-sorting', ['middleware' => 'acl:view_delivery','as' => 'admin.delivery.hub.store', 'uses' => 'DeliveryController@storeRiders']);
Route::post('hub-sorting-payment', ['middleware' => 'acl:view_delivery','as' => 'admin.delivery.hub.store.payment.received', 'uses' => 'DeliveryController@paymentReceivedStoreRiders']);
Route::get('invoice-pdf-download/{array}', ['as' => 'admin.delivery.invoice.pdf.download', 'uses' => 'DeliveryController@deliveryInvoiceDetailsPdf']);
Route::get('delivery/{array}/invoice-pdf', ['middleware' => 'acl:view_delivery', 'as' => 'admin.delivery.invoice.pdf', 'uses' => 'DeliveryController@generatePdf']);
Route::post('delivery/payment/received', ['middleware' => 'acl:view_delivery', 'as' => 'admin.delivery.payment.received', 'uses' => 'DeliveryController@paymentReceived']);

Route::post('delivery/check-user-by/recipient-number', ['middleware' => 'acl:view_delivery', 'as' => 'admin.delivery.check.user', 'uses' => 'DeliveryController@checkUserByNumber']);

//Amendment Delivery Sorting
Route::get('amendment-delivery', ['middleware' => 'acl:view_amendment_hub', 'as' => 'admin.amendment.delivery', 'uses' => 'DeliveryController@amendmentDelivery']);
Route::post('amendment-hub-sorting', ['middleware' => 'acl:view_amendment_hub','as' => 'admin.delivery.amendment-hub.store', 'uses' => 'DeliveryController@storeRidersAmendment']);
Route::post('amendment-hub-sorting-payment', ['middleware' => 'acl:view_amendment_hub','as' => 'admin.delivery.amendment-hub.store.payment.received', 'uses' => 'DeliveryController@paymentReceivedStoreRidersAmendment']);

// store
Route::get('store', ['middleware' => 'acl:view_store', 'as' => 'admin.stores', 'uses' => 'StoresController@index']);
Route::post('store-export', ['middleware' => 'acl:view_store', 'as' => 'admin.stores.export', 'uses' => 'StoresController@postExportFile']);
Route::get('store_data', ['middleware' => 'acl:view_store', 'as' => 'admin.datatable.store', 'uses' => 'StoresController@getDataTableReport']);

// product
Route::get('product', ['middleware' => 'acl:view_product', 'as' => 'admin.products', 'uses' => 'ProductsController@index']);
Route::post('product-export', ['middleware' => 'acl:view_product', 'as' => 'admin.products.export', 'uses' => 'ProductsController@postExportFile']);
Route::get('product-data', ['middleware' => 'acl:view_product', 'as' => 'admin.datatable.product', 'uses' => 'ProductsController@getDataTableReport']);

// invoice
Route::get('invoice', ['middleware' => 'acl:view_invoice', 'as' => 'admin.invoices', 'uses' => 'InvoiceController@index']);
Route::get('unpaid-invoice', ['middleware' => 'acl:view_invoice', 'as' => 'admin.unpaid.invoices', 'uses' => 'InvoiceController@unpaidInvoices']);
Route::get('invoice-export', ['middleware' => 'acl:view_invoice', 'as' => 'admin.invoices.export', 'uses' => 'InvoiceController@postExportFile']);
Route::get('invoice-data', ['middleware' => 'acl:view_invoice', 'as' => 'admin.datatable.invoice', 'uses' => 'InvoiceController@getDataTableReport']);
Route::get('unpaid-invoice-data', ['middleware' => 'acl:view_invoice', 'as' => 'admin.datatable.unpaid.invoice', 'uses' => 'InvoiceController@getUnpaidDataTableReport']);
Route::post('invoice-details', ['middleware' => 'acl:view_invoice', 'as' => 'admin.invoice.details.view', 'uses' => 'InvoiceController@viewInvoices']);
Route::get('invoice-notes/{merchant_id}', ['middleware' => 'acl:view_invoice', 'as' => 'admin.invoice.notes', 'uses' => 'InvoiceController@invoiceNotes']);
Route::get('paid-invoice-notes/{merchant_id}/{invoice_id}', ['middleware' => 'acl:view_invoice', 'as' => 'admin.paid-invoice.notes', 'uses' => 'InvoiceController@invoiceNotes']);
Route::post('invoice-notes/store', ['middleware' => 'acl:view_invoice', 'as' => 'admin.invoice.note.store', 'uses' => 'InvoiceController@storeInvoiceNotes']);
Route::post('multiple-invoice-store', ['middleware' => 'acl:view_invoice', 'as' => 'admin.invoice.multiple.store', 'uses' => 'InvoiceController@storeMultipleInvoiceNotes']);

//Collected
Route::get('collected', ['middleware' => 'acl:view_collected', 'as' => 'admin.collected', 'uses' => 'CollectedController@index']);
Route::post('collected-export', ['middleware' => 'acl:view_collected', 'as' => 'admin.collected.export', 'uses' => 'CollectedController@postExportFile']);
Route::get('collected_data', ['middleware' => 'acl:view_collected', 'as' => 'admin.datatable.collected', 'uses' => 'CollectedController@getDataTableReport']);
Route::post('collected-products', ['middleware' => 'acl:view_collected', 'as' => 'admin.collected.product.view', 'uses' => 'CollectedController@viewProducts']);
Route::post('collected-approval', ['middleware' => 'acl:view_collected', 'as' => 'admin.collected.product.approval', 'uses' => 'CollectedController@productsApproval']);
//Route::post('hub-sorting', ['middleware' => 'acl:view_delivery','as' => 'admin.collected.hub.store', 'uses' => 'CollectedController@storeRiders']);

// hub
Route::get('hub', ['middleware' => 'acl:view_hub', 'as' => 'admin.hubs', 'uses' => 'HubController@index']);
Route::post('hub-export', ['middleware' => 'acl:view_hub', 'as' => 'admin.hubs.export', 'uses' => 'HubController@postExportFile']);
Route::get('hub_data', ['middleware' => 'acl:view_hub', 'as' => 'admin.datatable.hub', 'uses' => 'HubController@getDataTableReport']);
Route::get('hub/new', ['middleware' => 'acl:add_hub', 'as' => 'admin.hub.create', 'uses' => 'HubController@create']);
Route::post('hub/store', ['middleware' => 'acl:add_hub', 'as' => 'admin.hub.store', 'uses' => 'HubController@store']);
Route::get('hub/{id}/edit', ['middleware' => 'acl:edit_hub', 'as' => 'admin.hub.edit', 'uses' => 'HubController@edit']);
Route::post('hub/{id}/update', ['middleware' => 'acl:edit_hub', 'as' => 'admin.hub.update', 'uses' => 'HubController@update']);
Route::get('hub/{id}/delete', ['middleware' => 'acl:delete_hub', 'as' => 'admin.hub.delete', 'uses' => 'HubController@destroy']);

//Mail-Settings
Route::get('mail/configuration', ['middleware' => 'acl:view_mail_configure', 'as' => 'admin.mail.configure', 'uses' => 'SettingController@mailConfigure']);
Route::post('mail/configuration/update', ['middleware' => 'acl:edit_mail_configure', 'as' => 'admin.mail.configure.update', 'uses' => 'SettingController@mailConfigureUpdate']);


//Route::get('inv-details-migration', ['middleware' => 'acl:edit_mail_configure', 'as' => 'admin.mail.configure.update', 'uses' => 'InvoiceController@invDetailsMigration']);

// expense-category
Route::get('expense-category', ['middleware' => 'acl:view_expense-category', 'as' => 'admin.expense-categorys', 'uses' => 'ExpenseController@categoryList']);
Route::get('expense-category/new', ['middleware' => 'acl:add_expense-category', 'as' => 'admin.expense-category.new', 'uses' => 'ExpenseController@categoryCreate']);
Route::post('expense-category/store', ['middleware' => 'acl:add_expense-category', 'as' => 'admin.expense-category.store', 'uses' => 'ExpenseController@categoryStore']);
Route::get('expense-category/{id}/edit', ['middleware' => 'acl:edit_expense-category', 'as' => 'admin.expense-category.edit', 'uses' => 'ExpenseController@categoryEdit']);
Route::post('expense-category/{id}/update', ['middleware' => 'acl:edit_expense-category', 'as' => 'admin.expense-category.update', 'uses' => 'ExpenseController@categoryUpdate']);
Route::get('expense-category/{id}/delete', ['middleware' => 'acl:delete_expense-category', 'as' => 'admin.expense-category.delete', 'uses' => 'ExpenseController@categoryDestroy']);

// expense
Route::get('expense', ['middleware' => 'acl:view_expense', 'as' => 'admin.expenses', 'uses' => 'ExpenseController@index']);
Route::post('expense-export', ['middleware' => 'acl:view_expense', 'as' => 'admin.expenses.export', 'uses' => 'ExpenseController@postExportFile']);
Route::get('expense-data', ['middleware' => 'acl:view_expense', 'as' => 'admin.datatable.expense', 'uses' => 'ExpenseController@getDataTableReport']);
Route::get('expense/new', ['middleware' => 'acl:add_expense', 'as' => 'admin.expense.new', 'uses' => 'ExpenseController@create']);
Route::post('expense/store', ['middleware' => 'acl:add_expense', 'as' => 'admin.expense.store', 'uses' => 'ExpenseController@store']);
Route::get('expense/{id}/edit', ['middleware' => 'acl:edit_expense', 'as' => 'admin.expense.edit', 'uses' => 'ExpenseController@edit']);
Route::post('expense/{id}/update', ['middleware' => 'acl:edit_expense', 'as' => 'admin.expense.update', 'uses' => 'ExpenseController@update']);
Route::get('expense/{id}/delete', ['middleware' => 'acl:delete_expense', 'as' => 'admin.expense.delete', 'uses' => 'ExpenseController@destroy']);

// income
Route::get('income', ['middleware' => 'acl:view_income', 'as' => 'admin.incomes', 'uses' => 'IncomeController@index']);
Route::post('income-export', ['middleware' => 'acl:view_income', 'as' => 'admin.incomes.export', 'uses' => 'IncomeController@postExportFile']);
Route::get('income-data', ['middleware' => 'acl:view_income', 'as' => 'admin.datatable.income', 'uses' => 'IncomeController@getDataTableReport']);

// financial Statement
Route::get('financial-statement', ['middleware' => 'acl:view_financial_statement', 'as' => 'admin.financial.statement', 'uses' => 'FinancialStatementController@index']);
Route::post('financial-statement', ['middleware' => 'acl:view_financial_statement', 'as' => 'admin.financial.statement.search', 'uses' => 'FinancialStatementController@index']);

// bar code
Route::get('generate-bar-code', ['middleware' => 'acl:view_barcode', 'as' => 'admin.generate.barcode', 'uses' => 'FinancialStatementController@barcode']);

//Import CSV/Excel File
Route::get('import-files', ['middleware' => 'acl:view_barcode', 'as' => 'admin.import.files', 'uses' => 'ImportFilesController@index']);
Route::post('save-files', ['middleware' => 'acl:view_barcode', 'as' => 'admin.import.save.files', 'uses' => 'ImportFilesController@parsingFiles']);