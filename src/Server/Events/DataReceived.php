<?php


namespace YoungOnes\Lightspeed\Server\Events;


use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DataReceived
{
    use Dispatchable, SerializesModels;

    public string $data;
    public string $remoteAddress;

    public function __construct(string $remoteAddress, string $data)
    {
        $this->data = $data;
        $this->remoteAddress = $remoteAddress;
    }
}