<?php

// attibute
Route::group(['prefix' => 'attributes', 'namespace' => 'Dashboard'], function () {
    Route::get('/', 'AttributeController@index')
        ->name('dashboard.attributes.index');
        // ->middleware(['permission:show_attributes']);

    Route::get('datatable', 'AttributeController@datatable')
        ->name('dashboard.attributes.datatable');
        // ->middleware(['permission:show_attributes']);

    Route::get('create', 'AttributeController@create')
        ->name('dashboard.attributes.create');
        // ->middleware(['permission:add_attributes']);

    Route::post('/', 'AttributeController@store')
        ->name('dashboard.attributes.store');
        // ->middleware(['permission:add_attributes']);

    Route::get('{id}/edit', 'AttributeController@edit')
        ->name('dashboard.attributes.edit');
        // ->middleware(['permission:edit_attributes']);

    Route::put('{id}', 'AttributeController@update')
        ->name('dashboard.attributes.update');
        // ->middleware(['permission:edit_attributes']);

    Route::delete('{id}', 'AttributeController@destroy')
        ->name('dashboard.attributes.destroy');
        // ->middleware(['permission:delete_attributes']);

    Route::get('deletes', 'AttributeController@deletes')
        ->name('dashboard.attributes.deletes')
        ->middleware(['permission:delete_all']);

    Route::get('{id}', 'AttributeController@show')
        ->name('dashboard.attributes.show');
        // ->middleware(['permission:show_attributes']);
});
