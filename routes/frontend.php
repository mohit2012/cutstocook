<?php

Auth::routes();
Route::get('/', 'LicenseController@home');
Route::get('forgot_password','frontend\LoginController@forgot_password');
Route::post('user_forgot_password','frontend\LoginController@user_forgot_password');
Route::post('user_login','frontend\LoginController@user_login');
Route::post('user_register','frontend\LoginController@user_register');
Route::get('terms_and_condition','frontend\LoginController@terms_condition');
Route::get('how_it_works','frontend\LoginController@how_it_works');
Route::get('privacy','frontend\LoginController@privacy');
Route::get('about_us','frontend\LoginController@about_us');
Route::get('food', 'frontend\DisplayController@food_display');
Route::get('all_restaurants', 'frontend\DisplayController@all_rest');
Route::get('offer', 'frontend\DisplayController@offer');
Route::get('restaurant_profile/{id}', 'frontend\DisplayController@restaurant_profile');
Route::post('add_cart', 'frontend\DisplayController@add_cart');
Route::get('order_list', 'frontend\DisplayController@order_list');
Route::get('remove_cart','frontend\DisplayController@remove_cart');
Route::post('update_cart','frontend\DisplayController@update_cart');
Route::get('restaurants_information/{id}', 'frontend\DisplayController@restaurants_information');
Route::post('filter','frontend\DisplayController@filter');
Route::post('itemfilter','frontend\DisplayController@itemfilter');
Route::post('addBookmark','frontend\DisplayController@addBookmark');
Route::post('search','frontend\DisplayController@search');
Route::get('promo_code','frontend\DisplayController@promo_code');
Route::post('removeSingleItem','frontend\DisplayController@removeSingleItem');
Route::get('category_shop/{category_id}','frontend\DisplayController@category_shop');

Route::middleware('auth')->group(function ()
{
    Route::get('order_confirmation', 'frontend\DisplayController@order_confirmation');
    Route::post('apply_coupen','frontend\DisplayController@apply_coupen');
    Route::resource('user_address','frontend\AddressController');
    Route::post('frontendStripePayment','frontend\DisplayController@frontendStripePayment');
    Route::post('confirm_order','frontend\DisplayController@confirm_order');
    Route::post('razor','frontend\DisplayController@razor');
    Route::get('settings','frontend\UserSettingController@settings');
    Route::post('update_profile','frontend\UserSettingController@update_profile');
    Route::post('notification','frontend\UserSettingController@notification');
    Route::post('getUserToken','frontend\UserSettingController@getUserToken');
    Route::get('user_details','frontend\UserSettingController@user_details');
    Route::post('edit_address','frontend\UserSettingController@edit_address');
    Route::post('update_address','frontend\UserSettingController@update_address');
    Route::post('delete_address','frontend\UserSettingController@delete_address');
    Route::post('add_review','frontend\UserSettingController@add_review');
    Route::get('cancel_order/{id}','frontend\DisplayController@cancel_order');
    Route::get('cancel_grocery_order/{id}','frontend\GroceryController@cancel_grocery_order');
    Route::get('grocery_order_confirmation', 'frontend\DisplayController@grocery_order_confirmation');
    Route::post('grocery_confirm_order','frontend\frontend\GroceryController@grocery_confirm_order');
    Route::post('add_grocery_review','frontend\UserSettingController@add_grocery_review');
    Route::post('update_cover_image','frontend\UserSettingController@update_cover_image');
    Route::post('user_select_address','frontend\AddressController@user_select_address');
    Route::get('remove_coupen','frontend\GroceryController@remove_coupen');

    // Flutterwave
    Route::get('FlutterWavepayment/{id}/{type}','frontend\DisplayController@FlutterWavepayment');
    Route::get('transction_verify/{id}','frontend\DisplayController@transction_verify');

    // paystack
    Route::get('/paystackPayment/{id}','frontend\DisplayController@paystackPayment');
    Route::post('/pay', 'frontend\DisplayController@redirectToGateway')->name('pay');

    // paytm
    Route::get('/paytmPayment','frontend\DisplayController@paytmPayment');
    Route::post('payTm','frontend\DisplayController@payTm');
});

Route::get('grocery','frontend\GroceryController@display_grocery');
Route::get('single_grocery/{id}','frontend\GroceryController@single_grocery_profile');
Route::get('single_food/{id}','frontend\GroceryController@single_food_profile');

Route::get('grocery_item','frontend\GroceryController@all_grocery_item');
Route::get('category_item/{id}','frontend\GroceryController@category_item');
Route::post('display_category','frontend\GroceryController@display_category');
Route::get('grocery_stores','frontend\GroceryController@grocery_stores');
Route::get('grocery_shop/{id}','frontend\GroceryController@show_grocery_shop');
Route::post('grocery_filter','frontend\GroceryController@grocery_filter');
Route::post('add_grocery_cart','frontend\GroceryController@add_grocery_cart_v1');
Route::post('search_grocery','frontend\GroceryController@search_grocery');
Route::post('grocery_store_search','frontend\GroceryController@grocery_store_search');
Route::post('grocery_item_search','frontend\GroceryController@grocery_item_search');

Route::get('/home', 'frontend\HomeController@index')->name('home');

?>
