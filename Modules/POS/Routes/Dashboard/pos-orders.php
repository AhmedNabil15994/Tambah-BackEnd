<?php

Route::group(['prefix' => 'pos-orders', "namespace" => "Dashboard"], function () {

    Route::get('/', 'PosOrderController@getPosOrders')
        ->name('dashboard.pos_orders.index')
        ->middleware(['permission:show_orders']);

    Route::get('datatable', 'PosOrderController@posOrdersDatatable')
        ->name('dashboard.pos_orders.datatable')
        ->middleware(['permission:show_orders']);
});
