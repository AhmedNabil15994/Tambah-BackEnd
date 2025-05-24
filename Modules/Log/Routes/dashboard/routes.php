<?php
use Illuminate\Support\Facades\Route;

Route::name('dashboard.')->group(function () {

    Route::get('logs/datatable', 'LogController@datatable')
        ->name('logs.datatable')
        ->middleware(['permission:show_logs']);

    Route::get('logs/deletes', 'LogController@deletes')
        ->name('logs.deletes')
        ->middleware(['permission:show_logs']);

    Route::resource('logs', 'LogController')
        ->names('logs')
        ->only('index', 'destroy', 'show')
        ->middleware(['permission:show_logs']);
});
