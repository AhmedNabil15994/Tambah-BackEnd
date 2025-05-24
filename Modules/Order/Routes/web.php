<?php

use Illuminate\Support\Facades\Route;

/*
|================================================================================
|                             DRIVER ROUTES
|================================================================================
*/
Route::prefix('driver-dashboard')->middleware(['driver.auth', 'permission:driver_access'])->group(function () {

    /*foreach (File::allFiles(module_path('Order', 'Routes/Driver')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["orders.php"] as $value) {
        require_once(module_path('Order', 'Routes/Driver/' . $value));
    }

});

/*
|================================================================================
|                             Back-END ROUTES
|================================================================================
*/
Route::prefix('dashboard')->middleware(['dashboard.auth', 'permission:dashboard_access'])->group(function () {

    /*foreach (File::allFiles(module_path('Order', 'Routes/Dashboard')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["orders.php", "order-statuses.php"] as $value) {
        require_once(module_path('Order', 'Routes/Dashboard/' . $value));
    }

});

/*
|================================================================================
|                             FRONT-END ROUTES
|================================================================================
*/
Route::prefix('/')->group(function () {

    /*foreach (File::allFiles(module_path('Order', 'Routes/FrontEnd')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["orders.php"] as $value) {
        require_once(module_path('Order', 'Routes/FrontEnd/' . $value));
    }

});
