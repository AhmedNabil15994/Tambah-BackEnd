<?php

Route::group(['prefix' => 'wrapping-addons'], function () {

    Route::get('/', 'Dashboard\AddonsController@index')
        ->name('dashboard.wrapping_addons.index')
        ->middleware(['permission:show_wrapping_addons']);

    Route::get('datatable', 'Dashboard\AddonsController@datatable')
        ->name('dashboard.wrapping_addons.datatable')
        ->middleware(['permission:show_wrapping_addons']);

    Route::get('create', 'Dashboard\AddonsController@create')
        ->name('dashboard.wrapping_addons.create')
        ->middleware(['permission:add_wrapping_addons']);

    Route::post('/', 'Dashboard\AddonsController@store')
        ->name('dashboard.wrapping_addons.store')
        ->middleware(['permission:add_wrapping_addons']);

    Route::get('{id}/edit', 'Dashboard\AddonsController@edit')
        ->name('dashboard.wrapping_addons.edit')
        ->middleware(['permission:edit_wrapping_addons']);

    Route::put('{id}', 'Dashboard\AddonsController@update')
        ->name('dashboard.wrapping_addons.update')
        ->middleware(['permission:edit_wrapping_addons']);

    Route::delete('{id}', 'Dashboard\AddonsController@destroy')
        ->name('dashboard.wrapping_addons.destroy')
        ->middleware(['permission:delete_wrapping_addons']);

    Route::get('deletes', 'Dashboard\AddonsController@deletes')
        ->name('dashboard.wrapping_addons.deletes')
        ->middleware(['permission:delete_wrapping_addons', 'permission:delete_all']);

    Route::get('{id}', 'Dashboard\AddonsController@show')
        ->name('dashboard.wrapping_addons.show')
        ->middleware(['permission:show_wrapping_addons']);

});
