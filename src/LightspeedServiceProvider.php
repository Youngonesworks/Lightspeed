<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use YoungOnes\Lightspeed\Console\ServerCommand;
use YoungOnes\Lightspeed\Events\ConnectionClosed;
use YoungOnes\Lightspeed\Events\ConnectionEnded;
use YoungOnes\Lightspeed\Events\ConnectionError;
use YoungOnes\Lightspeed\Requests\Request;
use YoungOnes\Lightspeed\Server\Events\ClosedConnection;
use YoungOnes\Lightspeed\Server\Events\ClosingConnection;
use YoungOnes\Lightspeed\Server\Events\ConnectedToServer;
use YoungOnes\Lightspeed\Server\Events\DataReceived;
use YoungOnes\Lightspeed\Server\Events\ResponseSent;
use YoungOnes\Lightspeed\Server\Events\SendingResponse;

class LightspeedServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/lightspeed_server.php' => base_path('config/lightspeed_server.php'),
        ], 'lightspeed.config');

        if (class_exists('\Illuminate\Routing\Router')) {
            Router::macro('lightspeed', function ($uri, $action) {
                return $this->addRoute(Request::METHOD, $uri, $action);
            });
        }
    }

    /**
     * Register any package services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/lightspeed_server.php', 'lightspeed_server');

        // Register the service the package provides.
        $this->app->singleton('lightspeed', static function ($app) {
            return new Lightspeed();
        });

        $this->commands([ServerCommand::class]);

        $this->registerListeners();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['lightspeed'];
    }

    private function registerListeners(): void
    {
        Event::listen(
            ConnectedToServer::class,
            \YoungOnes\Lightspeed\Server\Listeners\ConnectedToServer::class
        );

        Event::listen(
            DataReceived::class,
            \YoungOnes\Lightspeed\Server\Listeners\DataReceived::class
        );

        Event::listen(
            SendingResponse::class,
            \YoungOnes\Lightspeed\Server\Listeners\SendingResponse::class
        );

        Event::listen(
            ResponseSent::class,
            \YoungOnes\Lightspeed\Server\Listeners\ResponseSent::class
        );

        Event::listen(
            ClosingConnection::class,
            \YoungOnes\Lightspeed\Server\Listeners\ClosingConnection::class
        );

        Event::listen(
            ClosedConnection::class,
            \YoungOnes\Lightspeed\Server\Listeners\ClosedConnection::class
        );

        Event::listen(
            \YoungOnes\Lightspeed\Client\Events\DataReceived::class,
            \YoungOnes\Lightspeed\Client\Listeners\DataReceived::class
        );

        Event::listen(
            ConnectionError::class,
            \YoungOnes\Lightspeed\Listeners\ConnectionError::class
        );

        Event::listen(
            ConnectionEnded::class,
            \YoungOnes\Lightspeed\Listeners\ConnectionEnded::class
        );

        Event::listen(
            ConnectionClosed::class,
            \YoungOnes\Lightspeed\Listeners\ConnectionClosed::class
        );
    }
}
