<?php
use Illuminate\Support\Facades\Route;

/*
|================================================================================
|                             Back-END ROUTES
|================================================================================
*/
Route::prefix('dashboard')->middleware(['dashboard.auth', 'permission:dashboard_access'])->group(function () {

    /*foreach (File::allFiles(module_path('Variation', 'Routes/Dashboard')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["options.php"] as $value) {
        require_once(module_path('Variation', 'Routes/Dashboard/' . $value));
    }

});

/*
|================================================================================
|                             FRONT-END ROUTES
|================================================================================
*/
Route::prefix('/')->group(function () {

    /*foreach (File::allFiles(module_path('Variation', 'Routes/FrontEnd')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["routes.php"] as $value) {
        require_once(module_path('Variation', 'Routes/FrontEnd/' . $value));
    }

});
