<?php

Route::group(['prefix' => 'barcode-setting'], function () {
    Route::get('/', 'Dashboard\BarcodeController@index')
        ->name('dashboard.barcode.index')
        ->middleware(['permission:show_barcode']);

    Route::get('datatable', 'Dashboard\BarcodeController@datatable')
        ->name('dashboard.barcode.datatable')
        ->middleware(['permission:show_barcode']);

    Route::get('create', 'Dashboard\BarcodeController@create')
        ->name('dashboard.barcode.create')
        ->middleware(['permission:add_barcode']);

    Route::post('/', 'Dashboard\BarcodeController@store')
        ->name('dashboard.barcode.store')
        ->middleware(['permission:add_barcode']);

    Route::get('{id}/edit', 'Dashboard\BarcodeController@edit')
        ->name('dashboard.barcode.edit')
        ->middleware(['permission:edit_barcode']);

    Route::put('{id}', 'Dashboard\BarcodeController@update')
        ->name('dashboard.barcode.update')
        ->middleware(['permission:edit_barcode']);

    Route::delete('{id}', 'Dashboard\BarcodeController@destroy')
        ->name('dashboard.barcode.destroy')
        ->middleware(['permission:delete_barcode']);

    Route::get('deletes', 'Dashboard\BarcodeController@deletes')
        ->name('dashboard.barcode.deletes')
        ->middleware(['permission:delete_barcode', 'permission:delete_all']);

    Route::get('{id}', 'Dashboard\BarcodeController@show')
        ->name('dashboard.barcode.show')
        ->middleware(['permission:show_barcode']);
});
