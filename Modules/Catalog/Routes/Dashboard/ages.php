<?php

Route::group(['prefix' => 'ages'], function () {

    Route::get('/', 'Dashboard\AgeController@index')
        ->name('dashboard.ages.index')
        ->middleware(['permission:show_ages']);

    Route::get('datatable', 'Dashboard\AgeController@datatable')
        ->name('dashboard.ages.datatable')
        ->middleware(['permission:show_ages']);

    Route::get('create', 'Dashboard\AgeController@create')
        ->name('dashboard.ages.create')
        ->middleware(['permission:add_ages']);

    Route::post('/', 'Dashboard\AgeController@store')
        ->name('dashboard.ages.store')
        ->middleware(['permission:add_ages']);

    Route::get('{id}/edit', 'Dashboard\AgeController@edit')
        ->name('dashboard.ages.edit')
        ->middleware(['permission:edit_ages']);

    Route::put('{id}', 'Dashboard\AgeController@update')
        ->name('dashboard.ages.update')
        ->middleware(['permission:edit_ages']);

    Route::delete('{id}', 'Dashboard\AgeController@destroy')
        ->name('dashboard.ages.destroy')
        ->middleware(['permission:delete_ages']);

    Route::get('deletes', 'Dashboard\AgeController@deletes')
        ->name('dashboard.ages.deletes')
        ->middleware(['permission:delete_ages', 'permission:delete_all']);

    Route::get('{id}', 'Dashboard\AgeController@show')
        ->name('dashboard.ages.show')
        ->middleware(['permission:show_ages']);

});
