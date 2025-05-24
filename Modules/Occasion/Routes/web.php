<?php
use Illuminate\Support\Facades\Route;


/*
|================================================================================
|                             Back-END ROUTES
|================================================================================
*/
Route::prefix('dashboard')->middleware(['dashboard.auth', 'permission:dashboard_access'])->group(function () {

    /*foreach (File::allFiles(module_path('Occasion', 'Routes/Dashboard')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["occasion.php"] as $value) {
        require_once(module_path('Occasion', 'Routes/Dashboard/' . $value));
    }

});

/*
|================================================================================
|                             FRONT-END ROUTES
|================================================================================
*/
Route::prefix('/')->group(function () {

    /*foreach (File::allFiles(module_path('Occasion', 'Routes/FrontEnd')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["occasion.php"] as $value) {
        require_once(module_path('Occasion', 'Routes/FrontEnd/' . $value));
    }

});
