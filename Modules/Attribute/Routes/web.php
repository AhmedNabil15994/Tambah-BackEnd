<?php


/*
|================================================================================
|                             VENDOR ROUTES
|================================================================================
*/
Route::prefix('vendor-dashboard')->middleware(['vendor.auth', 'permission:seller_access'])->group(function () {

    /*foreach (File::allFiles(module_path('Attribute', 'Routes/Vendor')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["attributes.php"] as $value) {
        require_once(module_path('Attribute', 'Routes/Vendor/' . $value));
    }

});


/*
|================================================================================
|                             Back-END ROUTES
|================================================================================
*/
Route::prefix('dashboard')->middleware(['dashboard.auth', 'permission:dashboard_access'])->group(function () {

    /*foreach (File::allFiles(module_path('Attribute', 'Routes/Dashboard')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["attributes.php"] as $value) {
        require_once(module_path('Attribute', 'Routes/Dashboard/' . $value));
    }

});

/*
|================================================================================
|                             FRONT-END ROUTES
|================================================================================
*/
Route::prefix('/')->group(function () {

    /*foreach (File::allFiles(module_path('Attribute', 'Routes/FrontEnd')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["attributes.php"] as $value) {
        require_once(module_path('Attribute', 'Routes/FrontEnd/' . $value));
    }

});
