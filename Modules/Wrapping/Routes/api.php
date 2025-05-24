<?php

Route::group(['prefix' => 'wrapping'], function () {

    Route::get('index', 'WebService\WrappingController@index')->name('api.wrapping.index');
    Route::post('wrap-products-with-gift', 'WebService\WrappingController@wrapProductsWithGift')->name('api.wrapping.wrap_products_with_gift');
    Route::post('select-addons', 'WebService\WrappingController@selectAddons')->name('api.wrapping.select_addons');

});
