<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'reports', "namespace"=> "Vendor"], function () {

    // product sales ========== ==============  
  	Route::get('/proudct-sales' ,'ReportController@porodctsSale')
  	->name('vendor.reports.proudct_sale')
    ->middleware(['permission:show_proudct_sale_reports']);

    Route::get('/proudct-sales/datatable' ,'ReportController@porodctsSaleDataTable')
  	->name('vendor.reports.proudct_sale_datatable')
    ->middleware(['permission:show_proudct_sale_reports']);

    // order sales ========== ==============  
  	Route::get('/order-sales' ,'ReportController@ordersSale')
  	->name('vendor.reports.order_sale')
    ->middleware(['permission:show_order_sale_reports']);

    Route::get('/order-sales/datatable' ,'ReportController@ordersSaleDataTable')
  	->name('vendor.reports.order_sale_datatable')
    ->middleware(['permission:show_order_sale_reports']);

    // refund product
    Route::get('/refund-products' ,'ReportController@refundSale')
        ->name('vendor.reports.refund_product')
        ->middleware(['permission:show_refund_product_reports']);

    Route::get('/refund-products/datatable' ,'ReportController@refundSaleDataTable')
        ->name('vendor.reports.proudct_refund_product_datatable')
        ->middleware(['permission:show_refund_product_reports']);

    // order refund ========== ==============  
  	Route::get('/order-refunds' ,'ReportController@refundOrders')
  	->name('vendor.reports.order_refund')
    ->middleware(['permission:show_order_refund_reports']);

    Route::get('/order-refunds/datatable' ,'ReportController@ordersRefundDataTable')
  	->name('vendor.reports.order_refund_datatable')
    ->middleware(['permission:show_order_refund_reports']);

    // refund product
    Route::get('/product-stock' ,'ReportController@productStock')
        ->name('vendor.reports.product_stock')
        ->middleware(['permission:show_product_stock_reports']);

    Route::get('/product-stock/datatable' ,'ReportController@productStockDataTable')
        ->name('vendor.reports.product_stock_datatable')
        ->middleware(['permission:show_product_stock_reports']);
    // vendors
    Route::get('/vendors' ,'ReportController@vendorTotal')
        ->name('vendor.reports.vendors')
        ->middleware(['permission:show_vendors_reports']);

    Route::get('/vendors/datatable' ,'ReportController@vendorTotalDataTable')
        ->name('vendor.reports.vendors_datatable')
        ->middleware(['permission:show_vendors_reports']);
});