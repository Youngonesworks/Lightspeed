<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Server;

use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Socket\ConnectionInterface;
use React\Socket\ServerInterface;
use React\Socket\TcpServer;
use Throwable;
use YoungOnes\Lightspeed\Events\ConnectionClosed;
use YoungOnes\Lightspeed\Events\ConnectionEnded;
use YoungOnes\Lightspeed\Events\ConnectionError;
use YoungOnes\Lightspeed\Exceptions\NotWriteableConnectionException;
use YoungOnes\Lightspeed\Payload\PayloadFactory;
use YoungOnes\Lightspeed\Payload\RequestPayload;
use YoungOnes\Lightspeed\Routing\LumenRouteResolver;
use YoungOnes\Lightspeed\Routing\RouteResolver;
use YoungOnes\Lightspeed\Server\Events\ClosedConnection;
use YoungOnes\Lightspeed\Server\Events\ClosingConnection;
use YoungOnes\Lightspeed\Server\Events\ConnectedToServer;
use YoungOnes\Lightspeed\Server\Events\DataReceived;
use YoungOnes\Lightspeed\Server\Events\ResponseSent;
use YoungOnes\Lightspeed\Server\Events\SendingResponse;

use function throw_unless;

class Server
{
    private ServerInterface $server;
    private LoopInterface $loop;

    public function __construct(string $uri)
    {
        $this->loop   = Factory::create();
        $this->server = new TcpServer($uri, $this->loop);

        $this->bindServerEvents();
        $this->loop->run();
    }

    private function bindServerEvents(): void
    {
        $this->server->on('connection', static function (ConnectionInterface $connection): void {
            event(new ConnectedToServer($connection->getRemoteAddress()));

            $connection->on('data', static function ($data) use ($connection): void {
                throw_unless($connection->isWritable(), NotWriteableConnectionException::class);

                event(new DataReceived($connection->getRemoteAddress(), $data));

                if (empty($data)) {
                    $connection->close();

                    return;
                }

                $requestPayload = RequestPayload::fromEncodedData($data);

                if (class_exists('\Laravel\Lumen\Routing\Router')) {
                    $resolvedRoute  = LumenRouteResolver::resolve($requestPayload);
                } else {
                    $resolvedRoute  = RouteResolver::resolve($requestPayload);
                }

                $response       = $resolvedRoute->run()->toResponse();
                $payload        = PayloadFactory::createFromResponse($response);


                event( new SendingResponse($connection->getRemoteAddress()));
                $connection->write($payload->getEncodedData());
                event(new ResponseSent($connection->getRemoteAddress()));
            });

            $connection->on('end', static function (): void {
                event( new ConnectionEnded());
            });

            $connection->on('error', static function (Throwable $exception): void {
                event(new ConnectionError($exception));
            });

            $connection->on('close', static function (): void {
                event(new ConnectionClosed());
            });
        });
    }
}
