<?php

//Route::view('/', 'landing-page');
Route::get('/','LandingPageController@index')->name('landing-page');
Route::get('/products','ShopController@index')->name('shop.index');
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
Route::view('/checkout', 'checkout');
Route::view('/thankyou', 'thankyou');
