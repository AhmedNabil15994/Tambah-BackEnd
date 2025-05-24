<?php

use Illuminate\Support\Facades\Route;

/*
|================================================================================
|                             Cashier  ROUTES
|================================================================================
*/

Route::group(["namespace" => "Cashier", "prefix" => "pos"], function () {

    foreach (["login.php", "home.php"] as $value) {
        require_once(module_path('POS', 'Routes/Cashier/' . $value));
    }
});




/*
|================================================================================
|                            VENDOR ROUTES
|================================================================================
*/
Route::prefix('vdashboard')->middleware(['vendor.auth', 'permission:seller_access'])->group(function () {

    foreach (["reports.php"] as $value) {
        require_once(module_path('POS', 'Routes/Vendor/' . $value));
    }
});
/*
|================================================================================
|                             Back-END ROUTES
|================================================================================
*/
Route::prefix('dashboard')->middleware(['dashboard.auth', 'permission:dashboard_access'])->group(function () {
    foreach (["labels.php", "barcode.php", "reports.php", "pos-orders.php"] as $value) {
        require_once(module_path('POS', 'Routes/Dashboard/' . $value));
    }
});

/*
|================================================================================
|                             FRONT-END ROUTES
|================================================================================
*/
Route::prefix('/')->group(function () {
});
