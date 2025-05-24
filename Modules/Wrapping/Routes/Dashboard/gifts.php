<?php

Route::group(['prefix' => 'gifts'], function () {

    Route::get('/', 'Dashboard\GiftController@index')
        ->name('dashboard.gifts.index')
        ->middleware(['permission:show_gifts']);

    Route::get('datatable', 'Dashboard\GiftController@datatable')
        ->name('dashboard.gifts.datatable')
        ->middleware(['permission:show_gifts']);

    Route::get('create', 'Dashboard\GiftController@create')
        ->name('dashboard.gifts.create')
        ->middleware(['permission:add_gifts']);

    Route::post('/', 'Dashboard\GiftController@store')
        ->name('dashboard.gifts.store')
        ->middleware(['permission:add_gifts']);

    Route::get('{id}/edit', 'Dashboard\GiftController@edit')
        ->name('dashboard.gifts.edit')
        ->middleware(['permission:edit_gifts']);

    Route::put('{id}', 'Dashboard\GiftController@update')
        ->name('dashboard.gifts.update')
        ->middleware(['permission:edit_gifts']);

    Route::delete('{id}', 'Dashboard\GiftController@destroy')
        ->name('dashboard.gifts.destroy')
        ->middleware(['permission:delete_gifts']);

    Route::get('deletes', 'Dashboard\GiftController@deletes')
        ->name('dashboard.gifts.deletes')
        ->middleware(['permission:delete_gifts', 'permission:delete_all']);

    Route::get('{id}', 'Dashboard\GiftController@show')
        ->name('dashboard.gifts.show')
        ->middleware(['permission:show_gifts']);

});
