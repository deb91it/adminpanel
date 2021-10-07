<?php
//Route::get('/', function () {
//    if (Auth::user()) {
////        if (get_logged_user_type() == '0') {
////            return redirect('/admin');
////        }
//        return redirect('/dashboard');
//    }
//    return view('agent.auth.login');
//});


Route::get('/', 'Admin\AdminAuthController@getLogin');
Route::post('admin/login', 'Admin\AdminAuthController@postLogin');
Route::get('admin/logout', 'Admin\AdminAuthController@getLogout');
Route::get('admin/lock', 'Admin\AdminAuthController@getLockScreen');
Route::post('admin/lock', 'Admin\AdminAuthController@postLockScreen');

//Merchant Registration
Route::get('merchant/registration', ['as' => 'admin.merchant.registration', 'uses' => 'Admin\MerchantController@registration']);
Route::post('merchant/store', ['as' => 'admin.merchant.store.registration', 'uses' => 'Admin\MerchantController@registrationStore']);
Route::get('merchant/completion', ['as' => 'admin.merchant.completion', 'uses' => 'Admin\MerchantController@successMsg']);

//Consignment Tracking
Route::get('consignment-tracking/{consignment_id?}', ['as' => 'admin.merchant.consignment', 'uses' => 'Api\DeliveryController@consignmentTrack']);

//Privacy Policy
Route::get('privacy-policy', ['as' => 'privacy.policy', 'uses' => 'Admin\AdminAuthController@privacyPolicy']);

//Admin Routes
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'lockscreen'] ], function()
{
    require_once(__DIR__ . "/Routes/Admin.php");
});

//Agent Routes
Route::group(['namespace' => 'Agent', 'middleware' => ['agent', 'lockscreen'] ], function()
{
    require_once(__DIR__ . "/Routes/Agent.php");
});

Route::post('vehicle-model-by-make', ['as' => 'agent.vehicle-model-by-make', 'uses' => 'Admin\VehicleModelController@vehicleModelByMake']);
Route::post('/', 'Admin\AdminAuthController@postLogin');
Route::get('lock', 'Admin\AdminAuthController@getAgentLockScreen');
Route::post('lock', 'Admin\AdminAuthController@postLockScreen');
Route::post('login', 'Agent\AgentAuthController@postLogin');

Route::group(['prefix' => 'v1',  'namespace' => 'Api' ], function()
{
    require_once(__DIR__ . "/Routes/Api.php");
});
