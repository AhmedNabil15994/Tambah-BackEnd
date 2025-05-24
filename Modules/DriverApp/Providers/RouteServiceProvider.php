<?php

namespace Modules\DriverApp\Providers;

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Core\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $moduleNamespace = 'Modules\DriverApp\Http\Controllers';

    protected function mapWebRoutes()
    {
        Route::middleware('web', 'EnableWebsiteRoutes', 'localizationRedirect', 'localeSessionRedirect', 'localeViewPath', 'localize')
            ->prefix(LaravelLocalization::setLocale())
            ->namespace($this->moduleNamespace)
            ->group(module_path('DriverApp', '/Routes/web.php'));
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api', 'EnableWebsiteRoutes')
            ->namespace($this->moduleNamespace)
            ->group(module_path('DriverApp', '/Routes/api.php'));
    }
}
