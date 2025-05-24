<?php

Route::group(['prefix' => 'occasion'], function () {

    Route::group(['prefix' => '/', 'middleware' => 'auth:api'], function () {

        Route::get('index', 'WebService\OccasionController@index')->name('api.occasions.index');
        Route::get('{id}', 'WebService\OccasionController@show')->name('api.occasions.show');
        Route::post('store', 'WebService\OccasionController@store')->name('api.occasions.store');
        Route::post('update/{id}', 'WebService\OccasionController@update')->name('api.occasions.update');
        Route::post('destroy/{id}', 'WebService\OccasionController@destroy')->name('api.occasions.destroy');

    });

});
