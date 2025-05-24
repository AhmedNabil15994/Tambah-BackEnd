<?php
Route::group(['prefix' => 'pos-reports', "namespace" => "Dashboard"], function () {

  // orders cashier ========== ==============
  Route::get('/cashier-orders', 'ReportController@cashierOrders')
    ->name('dashboard.reports.cashier-orders')
    ->middleware(['permission:show_order_sale_reports']);

  Route::get('/cashier-orders/datatable', 'ReportController@cashierOrdersDataTable')
    ->name('dashboard.reports.cashier-orders')
    ->middleware(['permission:show_order_sale_reports']);
});
