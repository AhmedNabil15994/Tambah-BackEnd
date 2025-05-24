<?php
use Illuminate\Support\Facades\Route;


/*
|================================================================================
|                             Back-END ROUTES
|================================================================================
*/
Route::prefix('dashboard')->group(function () {

    /*foreach (File::allFiles(module_path('Authentication', 'Routes/Dashboard')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["login.php", "logout.php"] as $value) {
        require_once(module_path('Authentication', 'Routes/Dashboard/' . $value));
    }

});

Route::prefix('developer')->group(function () {

    foreach (["login.php", "logout.php"] as $value) {
        require_once(module_path('Authentication', 'Routes/Dashboard/' . $value));
    }

});

/*
|================================================================================
|                             FRONT-END ROUTES
|================================================================================
*/
Route::prefix('/')->group(function () {

    /*foreach (File::allFiles(module_path('Authentication', 'Routes/FrontEnd')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["login.php", "logout.php", "password.php", "register.php", "reset.php", "verification-code.php"] as $value) {
        require_once(module_path('Authentication', 'Routes/FrontEnd/' . $value));
    }

});
