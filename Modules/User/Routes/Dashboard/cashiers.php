<?php

Route::group(['prefix' => 'cashiers'], function () {

    Route::get('/', 'Dashboard\CashierController@index')
        ->name('dashboard.cashiers.index')
        ->middleware(['permission:show_cashiers']);

    Route::get('datatable', 'Dashboard\CashierController@datatable')
        ->name('dashboard.cashiers.datatable')
        ->middleware(['permission:show_cashiers']);

    Route::get('create', 'Dashboard\CashierController@create')
        ->name('dashboard.cashiers.create')
        ->middleware(['permission:add_cashiers']);

    Route::post('/', 'Dashboard\CashierController@store')
        ->name('dashboard.cashiers.store')
        ->middleware(['permission:add_cashiers']);

    Route::get('{id}/edit', 'Dashboard\CashierController@edit')
        ->name('dashboard.cashiers.edit')
        ->middleware(['permission:edit_cashiers']);

    Route::put('{id}', 'Dashboard\CashierController@update')
        ->name('dashboard.cashiers.update')
        ->middleware(['permission:edit_cashiers']);

    Route::delete('{id}', 'Dashboard\CashierController@destroy')
        ->name('dashboard.cashiers.destroy')
        ->middleware(['permission:delete_cashiers']);

    Route::get('deletes', 'Dashboard\CashierController@deletes')
        ->name('dashboard.cashiers.deletes')
        ->middleware(['permission:delete_cashiers', 'permission:delete_all']);

    Route::get('{id}', 'Dashboard\CashierController@show')
        ->name('dashboard.cashiers.show')
        ->middleware(['permission:show_cashiers']);
});
