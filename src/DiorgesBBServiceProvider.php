<?php

namespace Diorgesl\DiorgesBB;

use Illuminate\Support\ServiceProvider;

class DiorgesBBServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'diorgesl');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'diorgesl');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/diorgesbb.php', 'diorgesbb');

        // Register the service the package provides.
        $this->app->singleton('diorgesbb', function ($app) {
            return new DiorgesBB;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['diorgesbb'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/diorgesbb.php' => config_path('diorgesbb.php'),
        ], 'config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/diorgesl'),
        ], 'diorgesbb.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/diorgesl'),
        ], 'diorgesbb.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/diorgesl'),
        ], 'diorgesbb.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
