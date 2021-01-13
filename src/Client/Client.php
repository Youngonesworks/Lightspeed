<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Client;

use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Socket\ConnectionInterface;
use React\Socket\Connector;
use Throwable;
use YoungOnes\Lightspeed\Client\Events\DataReceived;
use YoungOnes\Lightspeed\Events\ConnectionClosed;
use YoungOnes\Lightspeed\Events\ConnectionEnded;
use YoungOnes\Lightspeed\Events\ConnectionError;
use YoungOnes\Lightspeed\Exceptions\NotWriteableConnectionException;
use YoungOnes\Lightspeed\Payload\PayloadFactory;
use YoungOnes\Lightspeed\Payload\ResponsePayload;
use YoungOnes\Lightspeed\Requests\Request;

use function throw_unless;

class Client
{
    private Connector $connector;
    private LoopInterface $loop;

    public function __construct()
    {
        $this->loop      = Factory::create();
        $this->connector = new Connector($this->loop);
    }

    public function send(Request $request, callable $callback): void
    {
        $payload = PayloadFactory::createFromRequest($request);

        $this->connector->connect($payload->getReceivingAddress())
            ->then(static function (ConnectionInterface $connection) use ($payload, $callback): void {
                throw_unless($connection->isWritable(), NotWriteableConnectionException::class);

                $connection->write($payload->getEncodedData());

                $connection->on('data', static function ($data) use ($callback, $connection): void {
                    DataReceived::dispatch();

                    $responsePayload = ResponsePayload::fromEncodedData($data);
                    $callback($responsePayload);

                    $connection->close();
                });

                $connection->on('end', static function (): void {
                    ConnectionEnded::dispatch();
                });

                $connection->on('error', static function (Throwable $exception): void {
                    ConnectionError::dispatch($exception);
                });

                $connection->on('close', static function (): void {
                    ConnectionClosed::dispatch();
                });
            });

        $this->loop->run();
    }
}
