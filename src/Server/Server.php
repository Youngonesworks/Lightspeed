<?php


namespace YoungOnes\Lightspeed\Server;

use CBOR\CBOREncoder;
use Illuminate\Support\Facades\Log;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Socket\ConnectionInterface;
use React\Socket\ServerInterface;
use React\Socket\TcpServer;
use YoungOnes\Lightspeed\Server\Events\ConnectedToServer;
use YoungOnes\Lightspeed\Server\Events\DataReceived;
use YoungOnes\Lightspeed\Server\Events\SendingResponse;
use YoungOnes\Lightspeed\Server\Events\ResponseSent;

class Server
{
    private ServerInterface $server;
    private LoopInterface $loop;

    public function __construct(string $uri)
    {
        $this->loop = Factory::create();
        $this->server = new TcpServer($uri, $this->loop);

        $this->bindServerEvents();
        $this->loop->run();
    }

    private function bindServerEvents()
    {
        $this->server->on('connection', static function (ConnectionInterface $connection) {
            ConnectedToServer::dispatch($connection->getRemoteAddress());

            $connection->on('data', static function ($data) use ($connection) {
                DataReceived::dispatch($connection->getRemoteAddress(), $data);

                if (empty($data)) {
                    $connection->close();
                    return;
                }

                $decodedData = CBOREncoder::decode($data);
                // TODO: Process
                $responseData = ['This', 'is', 'a', ['response']];
                $responseData = CBOREncoder::encode($responseData);

                SendingResponse::dispatch($connection->getRemoteAddress());
                $connection->write($responseData);
                ResponseSent::dispatch($connection->getRemoteAddress());
                $connection->close();
            });
        });

        $this->server->on('error', static function (\Exception $e) {
            $error = sprintf(
                'Uncaught exception "%s"([%d]%s) at %s:%s, %s%s',
                get_class($e),
                $e->getCode(),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                PHP_EOL,
                $e->getTraceAsString()
            );

            die($error);
        });
    }
}
