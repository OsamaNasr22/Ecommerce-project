<?php

//Route::view('/', 'landing-page');
Route::get('/','LandingPageController@index')->name('landing-page');
Route::get('/products/{categoryname?}/{sort?}','ShopController@index')->name('shop.index');
Route::get('/product/{slug}','ShopController@show')->name('shop.show');

//Route::view('/cart', 'cart');
Route::get('empty',function (){

    Cart::destroy();
});

Route::get('cart','CartController@index')->name('cart.index');
Route::delete('cart/{product}','CartController@destroy')->name('cart.destroy');
Route::post('cart/switchtosaveforlater/{id}','CartController@switchToSaveForLater')->name('cart.toSaveLater');
Route::delete('saveforlater/{id}','SaveForLaterController@destroy')->name('save.destroy');
Route::post('saveforlater/switchToCart/{id}','SaveForLaterController@switchToCart')->name('save.toCart');

Route::post('cart/{product}','CartController@store')->name('cart.store');
Route::patch('cart/{product}','CartController@update')->name('cart.update');
Route::get('/checkout','CheckoutController@index')->name('checkout.index')->middleware('auth');
Route::get('/guestcheckout','CheckoutController@index')->name('checkoutguest.index');
Route::post('/checkout','CheckoutController@store')->name('checkout.store');
Route::post('/coupon','CouponController@store')->name('coupon.store');
Route::delete('/coupon','CouponController@destroy')->name('coupon.destroy');
//Route::view('/checkout', 'checkout');
//Route::view('/thankyou', 'thankyou');
Route::get('/thankyou','ConfirmationController@index')->name('confirmation.index');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
