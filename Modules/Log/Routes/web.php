<?php

/*
|================================================================================
|                             Back-END ROUTES
|================================================================================
*/
Route::prefix('dashboard')->namespace('Dashboard')->middleware(['dashboard.auth', 'permission:dashboard_access'])->group(function () {

    foreach (["routes.php"] as $value) {
        require_once(module_path('Log', 'Routes/dashboard/' . $value));
    }

});