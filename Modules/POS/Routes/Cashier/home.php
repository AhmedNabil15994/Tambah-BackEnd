<?php
use Illuminate\Support\Facades\Route;

Route::group(["middleware"=>['cashier.auth',
//    'permission:cashiers_access'
]], function () {

    Route::get('/', 'CashierController@index')->name('cashier.home');
    Route::get('/test', 'CashierController@test');
    Route::post('/updateProfile', 'CashierController@updateProfile')->name('cashier.update-profile');
    
     // order cycles
     Route::group([ 'prefix' => 'orders'],function(){
        
        Route::get('/', 'CashierController@myOrder')->name('cashier.orders.list');
        Route::post('/', 'OrderController@create')->name('cashier.orders.create');
        Route::post('refund', 'CashierController@refundOrder')->name('cashier.orders.refund');
        Route::get('/{id}/invoice', 'CashierController@invoice')->name('cashier.orders.invoice');
    });
    
     // users cycles
    Route::group([ 'prefix' => 'users'],function(){
        Route::post('/', 'CashierController@addUser')->name('cashier.users.store');
        Route::put('{id}', 'CashierController@editUser')->name('cashier.users.update');
    });

    // address cycles
    Route::group([ 'prefix' => 'addresses'],function(){
        Route::get('area/cities', 'CashierController@cities')->name('cashier.address.area.cities');
        
        Route::get('list/{userId}', 'UserAddressController@list')->name('cashier.address.list');
        Route::get('/show/{id}', 'UserAddressController@getAddressById')->name('cashier.address.show');
        Route::post('/', 'UserAddressController@create')->name('cashier.address.store');
        Route::put('{id}', 'UserAddressController@update')->name('cashier.address.update');
        Route::delete('{id}', 'UserAddressController@delete')->name('cashier.address.delete');
    });

    Route::group(['prefix' => 'coupons'], function () {

        Route::post('/check_coupon' ,'CouponController@checkCoupon')->name('cashier.check_coupon');
    });
});



Route::group(['prefix' => 'catalog'], function () {


        Route::get('generate-sku', 'CatalogController@generateSku')->name('pos.generate_sku');
        Route::get('all-categories', 'CatalogController@getAllCategories')->name('pos.categories.list');
        Route::get('products', 'CatalogController@getProductsByCategory');
        Route::get('product/{id}/details', 'CatalogController@getProductDetails');

        Route::get('all-brands', 'BrandController@getAllBrands')->name('pos.categories.brands');
});