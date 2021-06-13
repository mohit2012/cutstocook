<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/save-token','CustomerController@saveToken');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/login','CustomerController@userLogin' );
Route::post('/user/register','CustomerController@userRegister' );
Route::post('/user/verifyPhone','CustomerController@verifyPhone' );
Route::post('/user/resendOTP','CustomerController@resendOTP' );
Route::post('/user/checkOtp','CustomerController@checkOtp' );
Route::post('/user/forgetPassword','CustomerController@forgerUserPassword' );
Route::post('/driver/forgetPassword','CustomerController@forgerUserPassword' );
Route::get('/user/keySetting','CompanySettingController@keySetting' );
Route::get('/driver/keySetting','CompanySettingController@keySetting' );
Route::get('/user/home','CustomerController@userHome' );
Route::get('/user/banner','BannerController@bannerImage' );
Route::post('/driver/login','CustomerController@driverLogin');
Route::post('/driver/verifyPhone','CustomerController@verifyDriverPhone' );
Route::post('/driver/register','CustomerController@driverRegister' );
Route::post('/driver/resendOTP','CustomerController@resendOTP' );
Route::post('/driver/checkOtp','CustomerController@checkDriverOtp' );
Route::get('/user/categoryShop/{id}','ShopController@categoryShop' );
Route::get('/user/categories','CategoryController@viewCategory' );
Route::get('/user/shops','ShopController@viewShop');
Route::get('/user/shopDetail/{id}','ShopController@shopDetail');
Route::get('/user/items/{id}','ItemController@viewShopItem' );
Route::get('/user/itemReview/{id}','ReviewController@itemReview' );
Route::get('/user/viewCoupon','CouponController@viewCoupon' );
Route::get('/user/viewGroceryCoupon','GroceryCouponController@viewGroceryCoupon' );
Route::get('/user/viewGroceryShopCoupon/{shop_id}','GroceryCouponController@viewGroceryShopCoupon' );
Route::post('/user/chkCoupon','CouponController@chkCoupon' );
Route::get('/user/viewShopCoupon/{shop_id}','CouponController@viewShopCoupon' );
Route::get('/user/trackOrder/{id}','OrderController@trackOrder' );
Route::get('/user/driverLocation/{id}','OrderController@driverLocation' );

Route::group(['middleware' => ['auth:api']], function () {
    // grocery
    Route::post('/user/createGroceryOrder','GroceryController@createGroceryOrder' );
    Route::post('/user/addGroceryReview','GroceryController@addGroceryReview' );
    Route::get('/user/groceryOrder','GroceryController@groceryOrder' );
    Route::get('/user/singleGroceryOrder/{order_id}','GroceryController@singleGroceryOrder' );
    Route::get('/user/trackGroceryOrder/{id}','GroceryController@trackOrder' );
    Route::get('/user/paytabPayment/{order_id}','GroceryController@paytabPayment' );
    Route::post('/user/deliveredProduct','GroceryController@deliveredProduct' );

    Route::get('/user/viewReview','ReviewController@viewUserReview' );
    Route::post('/user/addDriverReview','ReviewController@addDriverReview' );
    Route::post('/user/addShopReview','ReviewController@addShopReview' );
    Route::post('/user/addItemReview','ReviewController@addItemReview' );
    Route::post('/user/editProfile','CustomerController@userEditProfile' );
    Route::post('/user/changeImage','CustomerController@changeImage' );
    Route::post('/user/changePassword','CustomerController@changeUserPassword' );
    Route::get('/user/userOrder','OrderController@viewUserOrder' );
    Route::get('/user/singleOrder/{id}','OrderController@singleOrder');
    Route::post('/user/createOrder','OrderController@createOrder' );
    Route::post('/user/addBookmark','CustomerController@addUserBookmark' );
    Route::get('/user/viewFavourite','CustomerController@viewUserFavourite' );
    Route::get('/user/viewNotification','NotificationController@viewNotification' );
    Route::get('/user/userAddress','CustomerController@userAllAddress' );
    Route::post('/user/addAddress','CustomerController@addUserAddress' );
    Route::post('/user/editAddress','CustomerController@editUserAddress' );
    Route::get('/user/deleteAddress/{id}','CustomerController@deleteAddress' );
    Route::post('/user/saveSetting','CustomerController@saveUserSetting' );
    Route::get('/user/cancelOrder/{id}','OrderController@cancelOrder' );
    Route::post('/user/addPhoto','CustomerController@addPhoto' );
    Route::get('/user/friendsCode','CustomerController@friendsCode' );
    Route::get('/user/getAddress/{id}','CustomerController@getAddress' );
    Route::get('/user/setAddress/{id}','CustomerController@setAddress' );
    Route::get('/user/userDetail','CustomerController@userDetail' );

    Route::group(['prefix' => 'driver'], function () {
        Route::post('/acceptRequest','OrderController@acceptRequest' );
        Route::get('/driverTrip','OrderController@driverTrip' );
        Route::get('/driverReview','ReviewController@driverReview' );
        Route::get('/driverRequest','OrderController@driverRequest' );
        Route::get('/rejectDriverOrder/{order_id}','OrderController@rejectDriverOrder' );
        Route::get('/driverProfile','CustomerController@driverProfile' );
        Route::get('/changeAvaliableStatus/{status}','CustomerController@changeAvaliableStatus' );
        Route::post('/driverStatus','OrderController@driverStatus' );
        Route::get('/collectPayment/{id}','OrderController@collectPayment' );
        Route::post('/editDriverProfile','CustomerController@editDriverProfile' );
        Route::post('/driverSetting','CustomerController@driverSetting' );
        Route::post('/pickupFood','OrderController@pickupFood' );
        Route::get('/viewOrder/{id}','OrderController@viewOrderDetail' );
        Route::post('/driverCancelOrder','OrderController@driverCancelOrder' );
        Route::post('/earningHistroy','OrderController@earningHistroy' );
        Route::get('/viewnotification','OrderController@viewnotification' );
        Route::post('/saveDriverLocation','OrderController@saveDriverLocation' );

        //  grocery orders
        Route::get('/groceryOrderRequest','GroceryController@groceryOrderRequest' );
        Route::post('/acceptGroceryOrderRequest','GroceryController@acceptGroceryOrderRequest' );
        Route::post('/driverGroceryStatus','GroceryController@driverGroceryStatus' );
        Route::get('/collectGroceryPayment/{id}','GroceryController@collectGroceryPayment' );
        Route::post('/pickupGrocery','GroceryController@pickupGrocery' );
        Route::get('/viewGroceryOrder/{id}','GroceryController@viewGroceryOrderDetail' );
        Route::post('/driverCancelGroceryOrder','GroceryController@driverCancelGroceryOrder' );
        Route::get('/rejectGroceryOrder/{order_id}','GroceryController@rejectGroceryOrder' );
    });
});

// grocery api
Route::get('/user/groceryCategory','GroceryController@groceryCategory' );
Route::get('/user/grocerySubCategory/{category_id}','GroceryController@grocerySubCategory' );
Route::get('/user/groceryShop','GroceryController@groceryShop' );
Route::get('/user/groceryShopDetail/{shop_id}','GroceryController@groceryShopDetail' );
Route::get('/user/groceryItem/{shop_id}','GroceryController@groceryItem' );
Route::get('/user/groceryItemDetail/{item_id}','GroceryController@groceryItemDetail' );
Route::get('/user/groceryShopCategory/{shop_id}','GroceryController@groceryShopCategory' );
Route::get('/user/groceryShop/{category_id}','GroceryController@groceryCategoryShop' );

// owner api
Route::get('/ownerSetting','OwnerApiController@ownerSetting' );
Route::post('/owner/login','OwnerApiController@ownerLogin' );
Route::group(['prefix' => 'owner','middleware' => ['auth:ownerApi']], function () {
    Route::get('/dashboard','OwnerApiController@ownerDashboard' );
    Route::get('/orders','OwnerApiController@viewOrders' );
    Route::get('/order-detail/{id}','OwnerApiController@singleOrder' );
    Route::get('/grocery-order-detail/{id}','OwnerApiController@grocerySingleOrder' );
    Route::get('/notification','OwnerApiController@notification' );
    Route::post('/change-status','OwnerApiController@changeStatus' );
    Route::post('/change-grocery-status','OwnerApiController@changeGroceryStatus' );
    Route::get('/shops','OwnerApiController@shops' );
    Route::get('/earning','OwnerApiController@earning' );
    Route::get('/clear-notification','OwnerApiController@clearNotification' );
});
