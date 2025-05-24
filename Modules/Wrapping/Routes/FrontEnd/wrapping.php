<?php

Route::group(['prefix' => 'wrapping'], function () {

    Route::get('/', 'FrontEnd\WrappingController@index')
        ->name('frontend.wrapping.index')
        ->middleware(['empty.cart']);

});
