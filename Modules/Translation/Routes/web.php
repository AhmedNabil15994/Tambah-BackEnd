<?php

/*
|================================================================================
|                             Back-END ROUTES
|================================================================================
*/
Route::prefix('dashboard')->middleware(['dashboard.auth', 'permission:dashboard_access'])->group(function () {

    /*foreach (File::allFiles(module_path('Translation', 'Routes/Dashboard')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["translations.php"] as $value) {
        require_once(module_path('Translation', 'Routes/Dashboard/' . $value));
    }

});
