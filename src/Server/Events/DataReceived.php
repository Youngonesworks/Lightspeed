<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Server\Events;

class DataReceived
{
    public string $data;
    public string $remoteAddress;

    public function __construct(string $remoteAddress, string $data)
    {
        $this->data          = $data;
        $this->remoteAddress = $remoteAddress;
    }
}
