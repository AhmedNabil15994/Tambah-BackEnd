<?php
use Illuminate\Support\Facades\Route;

/*
|================================================================================
|                            Dashboard ROUTES
|================================================================================
*/
Route::prefix('dashboard')->middleware(['dashboard.auth', 'permission:dashboard_access'])->group(function () {

    /*foreach (File::allFiles(module_path('Apps', 'Routes/Dashboard')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["home.php"] as $value) {
        require_once(module_path('Apps', 'Routes/Dashboard/' . $value));
    }

});

/*
|================================================================================
|                             FRONT-END ROUTES
|================================================================================
*/
Route::prefix('/')->group(function () {

    /*foreach (File::allFiles(module_path('Apps', 'Routes/FrontEnd')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["home.php", "contact-us.php"] as $value) {
        require_once(module_path('Apps', 'Routes/FrontEnd/' . $value));
    }

});
