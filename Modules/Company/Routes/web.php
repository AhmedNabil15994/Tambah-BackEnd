<?php

use Illuminate\Support\Facades\Route;


/*
|================================================================================
|                             Back-END ROUTES
|================================================================================
*/
Route::prefix('dashboard')->middleware(['dashboard.auth', 'permission:dashboard_access'])->group(function () {

    /*foreach (File::allFiles(module_path('Company', 'Routes/Dashboard')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["company.php", "delivery-charges.php"] as $value) {
        require_once(module_path('Company', 'Routes/Dashboard/' . $value));
    }

});

/*
|================================================================================
|                             FRONT-END ROUTES
|================================================================================
*/
Route::prefix('/')->group(function () {

    /*foreach (File::allFiles(module_path('Company', 'Routes/FrontEnd')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["company.php"] as $value) {
        require_once(module_path('Company', 'Routes/FrontEnd/' . $value));
    }

});
