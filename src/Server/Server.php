<?php


namespace YoungOnes\Lightspeed\Server;

use CBOR\CBOREncoder;
use Illuminate\Http\JsonResponse;
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
use YoungOnes\Lightspeed\Events\ConnectionClosed;
use YoungOnes\Lightspeed\Events\ConnectionEnded;
use YoungOnes\Lightspeed\Events\ConnectionError;
use YoungOnes\Lightspeed\Exceptions\NotWriteableConnectionException;
use YoungOnes\Lightspeed\Payload\PayloadFactory;
use YoungOnes\Lightspeed\Payload\RequestPayload;
use YoungOnes\Lightspeed\Routing\RouteResolver;
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

                $requestPayload = RequestPayload::fromEncodedData($data);
                $resolvedRoute = RouteResolver::resolve($requestPayload);
                $response = $resolvedRoute->run()->toResponse();
                $payload = PayloadFactory::createFromResponse($response);

                ray($payload);

                throw_unless($connection->isWritable(), NotWriteableConnectionException::class);
                SendingResponse::dispatch($connection->getRemoteAddress());
                ray($payload->getEncodedData());
//                $connection->write('ddd');
                $ff = $connection->write($payload->getEncodedData());

                ray('ff', $ff);
                ResponseSent::dispatch($connection->getRemoteAddress());

//                ClosingConnection::dispatch($connection->getRemoteAddress());
//// TODO: Fixme
//                sleep(3);
                $connection->end();
//                $connection->close();
//                ClosedConnection::dispatch();
            });

            $connection->on('end', function () {
                ConnectionEnded::dispatch();
            });

            $connection->on('error', function (\Exception $exception) {
                ConnectionError::dispatch($exception);
            });

            $connection->on('close', function () {
                ConnectionClosed::dispatch();
            });
        });
    }
}
