<?php


namespace YoungOnes\Lightspeed\Server\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConnectedToServer
{
    use Dispatchable, SerializesModels;

    public string $remoteAddress;

    public function __construct(string $remoteAddress)
    {
        $this->remoteAddress = $remoteAddress;
    }
}
