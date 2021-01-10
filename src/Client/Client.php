<?php


namespace YoungOnes\Lightspeed\Client;


use Illuminate\Support\Facades\Log;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Socket\ConnectionInterface;
use React\Socket\Connector;
use YoungOnes\Lightspeed\Exceptions\NotWriteableConnectionException;
use YoungOnes\Lightspeed\Requests\PendingRequest;
use YoungOnes\Lightspeed\Requests\Request;

class Client
{
    private string $socketUri;
    private Connector $connector;
    private LoopInterface $loop;

    private function __construct(PendingRequest $request)
    {
        $this->socketUri = $request->getSocketUri();
        $this->loop = Factory::create();
        $this->connector = new Connector($this->loop);
    }

    public static function send(Request $request): self
    {
        $pendingRequest = new PendingRequest($request);
        $instance = new static($pendingRequest);

        $instance->connector->connect($pendingRequest->getSocketUri())
            ->then(static function(ConnectionInterface $connection) use ($pendingRequest) {
                throw_unless($connection->isWritable(), NotWriteableConnectionException::class);
                $r = $connection->write($pendingRequest->getEncodedData());
//                $connection->on('data', function ($data) use ($connection) {
//                    Log::debug('Data received. Closing connection.');
//                    $connection->close();
//                });
            });

        $instance->loop->run();

        return $instance;
    }

}