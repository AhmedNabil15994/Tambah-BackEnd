<?php
use Illuminate\Support\Facades\Route;
/*
|================================================================================
|                             Back-END ROUTES
|================================================================================
*/
Route::prefix('dashboard')->middleware(['dashboard.auth', 'permission:dashboard_access'])->group(function () {

    /*foreach (File::allFiles(module_path('Catalog', 'Routes/Dashboard')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["categories.php", "products.php", "search-keywords.php", "ages.php", "brands.php", "vendors.php"] as $value) {
        require_once(module_path('Catalog', 'Routes/Dashboard/' . $value));
    }

});

/*
|================================================================================
|                             FRONT-END ROUTES
|================================================================================
*/
Route::prefix('/')
//    ->middleware('cacheResponse')
    ->group(function () {

    /*foreach (File::allFiles(module_path('Catalog', 'Routes/FrontEnd')) as $file) {
        require_once($file->getPathname());
    }*/

    foreach (["categories.php", "address.php", "checkout.php", "filter.php", "search.php", "shopping-cart.php", "products.php"] as $value) {
        require_once(module_path('Catalog', 'Routes/FrontEnd/' . $value));
    }

});
