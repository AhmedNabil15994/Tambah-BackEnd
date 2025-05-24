<?php

Route::group(['prefix' => 'occasion'], function () {

    Route::get('/', 'Dashboard\OccasionController@index')
        ->name('dashboard.occasions.index')
        ->middleware(['permission:show_occasions']);

    Route::get('datatable', 'Dashboard\OccasionController@datatable')
        ->name('dashboard.occasions.datatable')
        ->middleware(['permission:show_occasions']);

    Route::get('create', 'Dashboard\OccasionController@create')
        ->name('dashboard.occasions.create')
        ->middleware(['permission:add_occasions']);

    Route::post('/', 'Dashboard\OccasionController@store')
        ->name('dashboard.occasions.store')
        ->middleware(['permission:add_occasions']);

    Route::get('{id}/edit', 'Dashboard\OccasionController@edit')
        ->name('dashboard.occasions.edit')
        ->middleware(['permission:edit_occasions']);

    Route::put('{id}', 'Dashboard\OccasionController@update')
        ->name('dashboard.occasions.update')
        ->middleware(['permission:edit_occasions']);

    Route::delete('{id}', 'Dashboard\OccasionController@destroy')
        ->name('dashboard.occasions.destroy')
        ->middleware(['permission:delete_occasions']);

    Route::get('deletes', 'Dashboard\OccasionController@deletes')
        ->name('dashboard.occasions.deletes')
        ->middleware(['permission:delete_occasions']);

    Route::get('{id}', 'Dashboard\OccasionController@show')
        ->name('dashboard.occasions.show')
        ->middleware(['permission:show_occasions']);

});
