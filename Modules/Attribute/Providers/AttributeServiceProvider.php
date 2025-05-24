<?php

namespace Modules\Attribute\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Blade;
use Modules\Attribute\Components\Attribute;

class AttributeServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerComponents();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('Attribute', 'Database/Migrations'));
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
    protected function registerComponents()
    {
        Blade::component('attributes-inputs', Attribute::class);
    }

    protected function registerConfig()
    {
        $this->publishes([
            module_path('Attribute', 'Config/config.php') => config_path('attribute.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Attribute', 'Config/config.php'), 'attribute'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/attribute');

        $sourcePath = module_path('Attribute', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/attribute';
        }, \Config::get('view.paths')), [$sourcePath]), 'attribute');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/attribute');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'attribute');
        } else {
            $this->loadTranslationsFrom(module_path('Attribute', 'Resources/lang'), 'attribute');
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
            $this->loadFactoriesFrom(module_path('Attribute', 'Database/factories'));
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
