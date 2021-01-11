<?php


namespace YoungOnes\Lightspeed\Server;

use CBOR\CBOREncoder;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Log;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Socket\ConnectionInterface;
use React\Socket\ServerInterface;
use React\Socket\TcpServer;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response;
use YoungOnes\Lightspeed\Payload\PayloadFactory;
use YoungOnes\Lightspeed\Server\Events\ClosedConnection;
use YoungOnes\Lightspeed\Server\Events\ClosingConnection;
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
                // TODO: Process request to hold decoded data
                $processedRequest = SymfonyRequest::create($decodedData['uri'], $decodedData['method'], $decodedData['parameters'] ?? []);
                $processedRequest = Request::createFromBase($processedRequest);
                /** @var Response $response */
                $response = app()->make(Router::class)->dispatch($processedRequest);
                dd($response->getContent());
                $responseData = PayloadFactory::createFromResponse($response);
                $responseData = CBOREncoder::encode($responseData);

                SendingResponse::dispatch($connection->getRemoteAddress());
                $connection->write($responseData);
                ResponseSent::dispatch($connection->getRemoteAddress());

                ClosingConnection::dispatch($connection->getRemoteAddress());
                $connection->close();
                ClosedConnection::dispatch();
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
