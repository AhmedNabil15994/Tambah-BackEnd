<?php
use Illuminate\Support\Facades\Route;


/*
|================================================================================
|                             Back-END ROUTES
|================================================================================
*/
Route::prefix('dashboard')->middleware(['dashboard.auth', 'permission:dashboard_access'])->group(function () {

    /*foreach (File::allFiles(module_path('Wrapping', 'Routes/Dashboard')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["addons.php", "cards.php", "gifts.php"] as $value) {
        require_once(module_path('Wrapping', 'Routes/Dashboard/' . $value));
    }

});

/*
|================================================================================
|                             FRONT-END ROUTES
|================================================================================
*/
Route::prefix('/')->group(function () {

    /*foreach (File::allFiles(module_path('Wrapping', 'Routes/FrontEnd')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["wrapping.php"] as $value) {
        require_once(module_path('Wrapping', 'Routes/FrontEnd/' . $value));
    }

});
