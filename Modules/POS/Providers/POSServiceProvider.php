<?php

namespace Modules\POS\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class POSServiceProvider extends ServiceProvider
{
    protected $middleware = [
        'Authentication' => [
            "cashier.auth" => "CashierAuthenticate",
        ],
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('POS', 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('POS', 'Config/config.php') => config_path('pos.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('POS', 'Config/config.php'),
            'pos'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/pos');

        $sourcePath = module_path('POS', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/pos';
        }, \Config::get('view.paths')), [$sourcePath]), 'pos');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/pos');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'pos');
        } else {
            $this->loadTranslationsFrom(module_path('POS', 'Resources/lang'), 'pos');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (!app()->environment('production') && $this->app->runningInConsole()) {
            $this->loadFactoriesFrom(module_path('POS', 'Database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
