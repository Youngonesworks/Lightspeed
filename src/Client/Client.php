<?php


namespace YoungOnes\Lightspeed\Client;


use CBOR\CBOREncoder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Socket\ConnectionInterface;
use React\Socket\Connector;
use YoungOnes\Lightspeed\Client\Events\DataReceived;
use YoungOnes\Lightspeed\Contracts\Payload\RequestPayloadContract;
use YoungOnes\Lightspeed\Events\ConnectionClosed;
use YoungOnes\Lightspeed\Events\ConnectionEnded;
use YoungOnes\Lightspeed\Events\ConnectionError;
use YoungOnes\Lightspeed\Exceptions\NotWriteableConnectionException;
use YoungOnes\Lightspeed\Payload\PayloadFactory;
use YoungOnes\Lightspeed\Payload\ResponsePayload;
use YoungOnes\Lightspeed\Requests\PendingRequest;
use YoungOnes\Lightspeed\Requests\Request;

class Client
{
    private Connector $connector;
    private LoopInterface $loop;

    public function __construct()
    {
        $this->loop = Factory::create();
        $this->connector = new Connector($this->loop);
    }

    public function send(Request $request)
    {
        $payload = PayloadFactory::createFromRequest($request);

        $this->connector->connect($payload->getReceivingAddress())
            ->then(static function(ConnectionInterface $connection) use ($payload) {
                throw_unless($connection->isWritable(), NotWriteableConnectionException::class);

                $connection->write($payload->getEncodedData());
//                $connection->end();

                $connection->on('data', function ($data) use ($connection) {
//                    $connection->end();
                    ray('djhkwdiu');
                    DataReceived::dispatch();
                    $responsePayload = ResponsePayload::fromEncodedData($data);
//                    $data = CBOREncoder::decode($data);


                    ray($responsePayload);
//                    new JsonResponse()
//                    $connection->close();
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

        $this->loop->run();
    }

}
