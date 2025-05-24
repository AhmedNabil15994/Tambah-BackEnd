<?php


/*
|================================================================================
|                             Back-END ROUTES
|================================================================================
*/
Route::prefix('dashboard')->middleware(['dashboard.auth', 'permission:dashboard_access'])->group(function () {

    /*foreach (File::allFiles(module_path('Area', 'Routes/Dashboard')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["cities.php", "countries.php", "states.php"] as $value) {
        require_once(module_path('Area', 'Routes/Dashboard/' . $value));
    }

});

/*
|================================================================================
|                             FRONT-END ROUTES
|================================================================================
*/
Route::prefix('/')->group(function () {

    foreach (["area.php"] as $value) {
        require_once(module_path('Area', 'Routes/FrontEnd/' . $value));
    }

});
