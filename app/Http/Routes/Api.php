<?php

    // Settings
    Route::post('settings-main', ['uses' => 'UtilsApiController@postAppSettings']);
    Route::post('settings-rider-main', ['uses' => 'UtilsApiController@postAppSettings']);

    // Auth
    Route::get('authenticate', ['uses' => 'AuthController@getVerifyAuthentication']);
    Route::post('check-mobile-no', ['uses' => 'AuthController@postCheckMobile']);
    Route::post('check-verification-code', ['uses' => 'AuthController@postVerifyCode']);
    Route::post('login', ['uses' => 'AuthController@postLogin']);
    Route::post('get-logout', ['uses' => 'AuthController@getLogout']);
    Route::post('change-password', ['middleware' => 'member_acl:execute_profile', 'uses' => 'AuthController@postChangePassword']);
    Route::post('forgot-password-request', ['uses' => 'AuthController@postForgotPasswordRequest']);
    Route::post('forgot-password', ['uses' => 'AuthController@postForgotPassword']);
    Route::get('profile/{user_id}/{user_type}', ['middleware' => 'member_acl:execute_profile','uses' => 'AuthController@getEmployeeData']);
    Route::post('profile/upload/', ['middleware' => 'member_acl:execute_profile', 'uses' => 'AuthController@postUploadProfileImage']);
    Route::post('profile-image-change', ['middleware' => 'member_acl:execute_profile', 'uses' => 'AuthController@profileImageUpdate']);

    //Profile
    Route::post('profile-update', ['middleware' => 'member_acl:execute_profile', 'uses' => 'AuthController@profileUpdate']);
    Route::post('profile-update-merchant', ['uses' => 'AuthController@merchantProfileUpdate']);

    // Settings
    Route::post('site-settings', ['uses' => 'UtilsApiController@siteSettings']);

    // Others
    Route::get('categories', ['uses' => 'CommonController@getCategoryList']);
    Route::get('zones', ['uses' => 'CommonController@getZoneList']);
    Route::get('plans', ['uses' => 'CommonController@getPlanList']);

    // Store
    Route::get('stores', ['middleware' => 'member_acl:view_store', 'uses' => 'StoreController@getStoreList']);
    Route::get('store/{id}', ['middleware' => 'member_acl:view_store', 'uses' => 'StoreController@getStoreById']);
    Route::post('store/add/{id?}', ['middleware' => 'member_acl:add_store', 'uses' => 'StoreController@postAddStore']);

    // Products
    Route::get('products', ['middleware' => 'member_acl:view_product', 'uses' => 'ProductController@getProductList']);
    Route::get('products-by-store', ['middleware' => 'member_acl:view_product', 'uses' => 'ProductController@getProductListByStoreId']);
    Route::get('product/{id}', ['middleware' => 'member_acl:view_product', 'uses' => 'ProductController@getProductById']);
    Route::post('product/store/{id?}', ['middleware' => 'member_acl:add_product', 'uses' => 'ProductController@postStoreProduct']);

    // Delivery
    Route::get('deliveries', ['middleware' => 'member_acl:view_delivery', 'uses' => 'DeliveryController@getDeliveryList']);
    Route::get('delivery/{id}', ['middleware' => 'member_acl:view_delivery', 'uses' => 'DeliveryController@getDeliveryById']);
    Route::post('delivery/store/{id?}', ['middleware' => 'member_acl:add_delivery', 'uses' => 'DeliveryController@postStoreDelivery']);

    // Invoice
    Route::get('invoices', ['middleware' => 'member_acl:view_invoice', 'uses' => 'InvoiceController@getInvoiceList']);
    Route::get('invoice-statistics', ['middleware' => 'member_acl:view_invoice', 'uses' => 'InvoiceController@getInvoiceStatistics']);
    Route::get('invoice/{date}', ['middleware' => 'member_acl:view_invoice', 'uses' => 'InvoiceController@getInvoiceByDate']);

    // Dashboard
    Route::get('dashboard/counted', ['middleware' => 'member_acl:view_merchant_dash', 'uses' => 'DashboardController@getCountedData']);
    Route::get('dashboard/graph', ['middleware' => 'member_acl:view_merchant_dash', 'uses' => 'DashboardController@getGraphData']);

    // Sms test
    Route::get('sms', [ 'uses' => 'DashboardController@getSendTestSms' ]);



    /* ***************************Rider API *********************** */

    //Pickup
    Route::post('pickup-dashboard', [ 'middleware' => 'member_acl:view_pickup', 'uses' => 'RiderController@getPickUpDashboard']);
    Route::post('pickup-lists', [ 'middleware' => 'member_acl:view_pickup', 'uses' => 'RiderController@getDeliveryLists']);
    Route::post('pickup-confirmation', [ 'middleware' => 'member_acl:view_pickup', 'uses' => 'RiderController@confirmPickup']);

    //Delivery
    Route::post('delivery-dashboard', [ 'middleware' => 'member_acl:view_pickup', 'uses' => 'RiderController@getDeliveryDashboard']);
    Route::post('delivery-lists', [ 'middleware' => 'member_acl:view_rider_delivery', 'uses' => 'RiderController@getDeliveryLists']);
    Route::post('delivery-confirmation', [ 'middleware' => 'member_acl:view_rider_delivery','uses' => 'RiderController@confirmDelivery']);

    //Products
    Route::get('product-lists/{delivery_id}', [ 'middleware' => 'member_acl:view_rider_product','uses' => 'RiderController@getProductListsByConsignmentId']);

    //Flag Status
    Route::get('flag-status', ['uses' => 'RiderController@getFlagStatus']);

    //Flag Status
    Route::post('send-sms-recipient', [ 'middleware' => 'member_acl:view_rider_status','uses' => 'RiderController@sendSmsRecipient']);

    Route::get('merchant-notification', [ 'middleware' => 'member_acl:view_merchant_notification','uses' => 'NotificationController@merchantNotification']);

