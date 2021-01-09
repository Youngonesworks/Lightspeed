<?php

namespace YoungOnes\Lightspeed;

use Illuminate\Support\ServiceProvider;

class LightspeedServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'youngones');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'youngones');
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
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/lightspeed.php', 'lightspeed');

        // Register the service the package provides.
        $this->app->singleton('lightspeed', function ($app) {
            return new Lightspeed;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['lightspeed'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/lightspeed.php' => config_path('lightspeed.php'),
        ], 'lightspeed.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/youngones'),
        ], 'lightspeed.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/youngones'),
        ], 'lightspeed.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/youngones'),
        ], 'lightspeed.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
