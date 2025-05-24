<?php
use Illuminate\Support\Facades\Route;

Route::name('dashboard.')->namespace('Dashboard')->group( function () {

    Route::get('subscribes/datatable'	,'SubscribeController@datatable')
        ->name('subscribes.datatable');

    Route::get('subscribes/deletes'	,'SubscribeController@deletes')
        ->name('subscribes.deletes', 'permission:delete_all');

    Route::get('subscribes/','SubscribeController@index')->name('subscribe.index');
    Route::delete('subscribes/{id}','SubscribeController@destroy')->name('subscribes.destroy');
});
