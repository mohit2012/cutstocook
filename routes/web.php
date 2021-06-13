<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/mainAdmin/login', 'LicenseController@viewMainAdminLogin')->name('mainAdmin/login');
Route::get('/owner/login', 'LicenseController@viewAdminLogin');
// Route::get('/', 'LicenseController@home');
Route::get('active', 'LicenseController@activeLicence');
Route::post('activeLicence', 'LicenseController@activeNewLicence');
Route::post('saveEnvData', 'AdminController@saveEnvData');
Route::post('saveAdminData', 'AdminController@saveAdminData');
Route::post('/getDeviceToken', 'CustomerController@getDeviceToken');
Route::post('/mainAdmin/login', 'LicenseController@chkAdmin_login');
Route::post('/owner/owner_login', 'AdminController@owner_login');
Route::get('/owner/ResetPassword', 'CustomerController@ResetPassword');
Route::post('/owner/reset_password', 'CustomerController@reset_password');

Route::group(['middleware' => ['auth'],'prefix' => 'owner'], function () {
    Route::get('/paytabsPayment', 'HomeController@paytabsPayment');
    Route::post('/check_payment', 'HomeController@check_payment');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/getShopData/{id}', 'ShopController@getShopData');

    Route::get('/viewUsers', 'CustomerController@viewUsers');
    Route::get('/ViewOrder', 'OrderController@viewOrder');
    Route::get('/ownerProfile', 'CustomerController@ownerProfileform');
    Route::post('/editOwnerProfile', 'CustomerController@editOwnerProfile');
    Route::post('/changeOwnerPassword', 'CustomerController@changeOwnerPassword');
    Route::post('/assignOrder', 'OrderController@assignOrder');
    Route::post('/changeOrderStatus', 'OrderController@changeOrderStatus');
    Route::post('/changeGroceryOrderStatus', 'GroceryOrderController@changeGroceryOrderStatus');
    Route::post('/changePaymentStatus', 'OrderController@changePaymentStatus');
    Route::get('/viewOrder/{id}', 'OrderController@viewsingleOrder');
    Route::get('/orderInvoice/{id}', 'OrderController@ordersInvoice');
    Route::get('/printInvoice/{id}', 'OrderController@printInvoice');
    Route::get('/accept-order/{id}', 'OrderController@accpetOrder');
    Route::get('/reject-order/{id}', 'OrderController@rejectOrder');
    Route::get('/accept-grocery-order/{id}', 'GroceryOrderController@accpetOrder');
    Route::get('/reject-grocery-order/{id}', 'GroceryOrderController@rejectOrder');
    Route::get('/viewGroceryOrder/{id}', 'GroceryOrderController@viewsingleOrder');
    Route::get('/groceryOrderInvoice/{id}', 'GroceryOrderController@orderInvoice');
    Route::get('/printGroceryInvoice/{id}', 'GroceryOrderController@printGroceryInvoice');
    Route::get('/shopReview/{shop_id}', 'ShopController@shopReviews');
    Route::get('/orderCreateNoti', 'OrderController@orderCreateNoti');
    Route::get('/notifications', 'CustomerController@viewNotifications');
    Route::get('/changeOwnerLanguage/{locale}', 'CustomerController@changeLanguage');
    Route::get('/ownerRevenueReport', 'CustomerController@ownerRevenueReport');
    Route::post('/ownerRevenueFilter', 'CustomerController@ownerRevenueFilter');
    Route::get('/groceryRevenueReport', 'GroceryOrderController@groceryRevenueReport');
    Route::post('/groceryRevenueFilter', 'CustomerController@groceryRevenueFilter');
    Route::get('/getPendingOrder', 'OrderController@getPendingOrder');
    Route::get('/shopCategory/{shop_id}', 'GroceryShopController@shopCategory');
    Route::get('/itemSubcategory/{category_id}', 'GroceryShopController@itemSubcategory');
    Route::post('/saveOwnerSetting', 'OwnerSettingController@saveOwnerSetting');

    Route::post('Category/importExcel','CategoryController@importExcel');
    Route::post('Shop/importShop','ShopController@importShop');
    Route::resources([
        'Category' => 'CategoryController',
        'Package' => 'PackageController',
        'GrocerySubCategory' => 'GrocerySubCategoryController',
        'GroceryShop'=>'GroceryShopController',
        'GroceryItem' => 'GroceryItemController',
        'GroceryOrder' => 'GroceryOrderController',
        'Item' => 'ItemController',
        'ShopOrder' => 'OrderController',
        'Shop' => 'ShopController',
        'Gallery' => 'GalleryController',
        'GroceryCoupon' => 'GroceryCouponController',
        'OwnerSetting' => 'OwnerSettingController',
    ]);
});

Route::group(['middleware' => 'mainAdmin','prefix' => 'mainAdmin'], function ()
{
    Route::get('/home', 'AdminController@admin_home')->name('mainAdmin/home');
    Route::get('/shopOwners', 'CustomerController@shopOwners');
    Route::get('/deliveryGuys', 'CustomerController@deliveryGuys');
    Route::get('/adminProfile', 'CustomerController@adminProfileform');
    Route::post('/editAdminProfile', 'CustomerController@editAdminProfile');
    Route::post('/changeAdminPassword', 'CustomerController@changeAdminPassword');
    Route::post('/savePaymentSetting', 'CompanySettingController@savePaymentSetting');
    Route::post('/saveLicense', 'LicenseController@saveLicenseSettings');
    Route::post('/saveWebContent', 'CompanySettingController@saveWebContent');

    Route::post('/saveNotificationSettings', 'CompanySettingController@saveNotificationSettings');
    Route::post('/saveWebNotificationSettings', 'CompanySettingController@saveWebNotificationSettings');
    Route::post('/saveMailSettings', 'CompanySettingController@saveMailSettings');
    Route::post('/saveSMSSettings', 'CompanySettingController@saveSMSSettings');
    Route::post('/saveSettings', 'CompanySettingController@saveSettings');
    Route::post('/firebaseSettings', 'CompanySettingController@firebaseSettings');
    Route::post('/savePointSettings', 'CompanySettingController@savePointSettings');
    Route::get('/changeLanguage/{locale}', 'CustomerController@changeLanguage');
    Route::post('/saveMapSettings', 'CompanySettingController@saveMapSettings');
    Route::post('/saveCommissionSettings', 'CompanySettingController@saveCommissionSettings');
    Route::post('/saveVerificationSettings', 'CompanySettingController@saveVerificationSettings');
    Route::post('/addDriver', 'CustomerController@addDriver');
    Route::get('/Delivery-guy/create', 'CustomerController@addDeliveryBoy');
    Route::get('/Driver/edit/{id}', 'CustomerController@editDriver');
    Route::post('/updateDriver/{id}', 'CustomerController@updateDriver');
    Route::post('/assignRadius', 'CustomerController@assignRadius');
    Route::get('/Customer/gallery/{id}', 'CustomerController@userGallery');
    Route::get('/customerReport', 'CustomerController@customerReport');
    Route::get('/revenueReport', 'OrderController@revenueReport');
    Route::get('/customer-loyalty-report', 'OrderController@customerLoyaltyReport');
    Route::get('/viewPointLog/{user_id}/{shop_id}', 'OrderController@viewUserPointLog');
    Route::post('/pointLogFilter', 'OrderController@pointLogFilter');
    Route::post('/revenueFilter', 'OrderController@revenueFilter');
    Route::post('/usersFilter', 'CustomerController@usersFilter');
    Route::post('/savePermission', 'RoleController@savePermission');
    Route::get('/add_notification', 'CustomerController@add_notification');
    Route::get('/module', 'CustomerController@module');
    Route::get('/addCoupons', 'CustomerController@addCoupons');
    Route::post('/changelangStatus', 'LanguageController@changelangStatus');
    Route::get('/shopReview/{shop_id}', 'ShopController@adminShopReview');

    // grocery
    Route::get('/viewGroceryShop', 'GroceryShopController@viewGroceryShop');
    Route::get('/groceryShopDetail/{shop_id}', 'GroceryShopController@groceryShopDetail');
    Route::get('/viewGroceryItem', 'GroceryItemController@viewGroceryItem');

    Route::post('AdminCategory/importExcel','CategoryController@importExcel');
    Route::post('GroceryCategory/importExcel','GroceryCategoryController@importExcel');

    Route::resources([
        'Location' => 'LocationController',
        'Role' => 'RoleController',
        'Permission' => 'PermissionController',
        'AdminCategory' => 'CategoryController',
        'AdminItem' => 'ItemController',
        'AdminShop' => 'ShopController',
        'Customer' => 'CustomerController',
        'Order' => 'OrderController',
        'adminSetting' => 'CompanySettingController',
        'Language' => 'LanguageController',
        // 'NotificationTemplate' => 'NotificationTemplateController',
        'GroceryCategory'=> 'GroceryCategoryController',
        'Banner' => 'BannerController',
    ]);
});

Route::group(['middleware' => 'mainAdmin','prefix' => 'mainAdmin'], function () {
    Route::resource('NotificationTemplate', 'NotificationTemplateController');
    Route::post('/sendTestMail', 'NotificationTemplateController@sendTestMail');
});

Route::group(['middleware' => ['auth'],'prefix' => 'owner'], function () {
    Route::resource('Coupon', 'CouponController');
});
