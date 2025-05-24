<?php


/*
|================================================================================
|                             Back-END ROUTES
|================================================================================
*/
Route::prefix('dashboard')->middleware(['dashboard.auth', 'permission:dashboard_access'])->group(function () {

    /*foreach (File::allFiles(module_path('Page', 'Routes/Dashboard')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["pages.php"] as $value) {
        require_once(module_path('Page', 'Routes/Dashboard/' . $value));
    }

});

// /*
// |================================================================================
// |                             FRONT-END ROUTES
// |================================================================================
// */

Route::prefix('/')->group(function () {

    /*foreach (File::allFiles(module_path('Page', 'Routes/FrontEnd')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["pages.php"] as $value) {
        require_once(module_path('Page', 'Routes/FrontEnd/' . $value));
    }

});
