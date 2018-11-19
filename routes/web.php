<?php

//Route::view('/', 'landing-page');
Route::get('/','LandingPageController@index')->name('landing-page');
Route::get('/products','ShopController@index')->name('shop.index');
Route::get('/product/{slug}','ShopController@show')->name('shop.show');
Route::view('/cart', 'cart');
Route::view('/checkout', 'checkout');
Route::view('/thankyou', 'thankyou');
