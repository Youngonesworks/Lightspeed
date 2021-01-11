<?php

namespace YoungOnes\Lightspeed;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use YoungOnes\Lightspeed\Console\ServerCommand;
use YoungOnes\Lightspeed\Server\Events\DataReceived;

class LightspeedServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/lightspeed_server.php' => config_path('lightspeed_server.php'),
        ], 'lightspeed.config');


    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/lightspeed_server.php', 'lightspeed_server');

        // Register the service the package provides.
        $this->app->singleton('lightspeed', function ($app) {
            return new Lightspeed;
        });

        $this->commands([
            ServerCommand::class
        ]);

        $this->registerListeners();
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

    private function registerListeners()
    {
        Event::listen(
            \YoungOnes\Lightspeed\Server\Events\ConnectedToServer::class,
            \YoungOnes\Lightspeed\Server\Listeners\ConnectedToServer::class
        );

        Event::listen(
            \YoungOnes\Lightspeed\Server\Events\DataReceived::class,
            \YoungOnes\Lightspeed\Server\Listeners\DataReceived::class
        );

        Event::listen(
            \YoungOnes\Lightspeed\Server\Events\SendingResponse::class,
            \YoungOnes\Lightspeed\Server\Listeners\SendingResponse::class
        );

        Event::listen(
            \YoungOnes\Lightspeed\Server\Events\ResponseSent::class,
            \YoungOnes\Lightspeed\Server\Listeners\ResponseSent::class
        );

        Event::listen(
            \YoungOnes\Lightspeed\Server\Events\ClosingConnection::class,
            \YoungOnes\Lightspeed\Server\Listeners\ClosingConnection::class
        );

        Event::listen(
            \YoungOnes\Lightspeed\Server\Events\ClosedConnection::class,
            \YoungOnes\Lightspeed\Server\Listeners\ClosedConnection::class
        );
    }
}
