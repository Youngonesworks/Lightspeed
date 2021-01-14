<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Server\Events;

class ConnectedToServer
{
    public string $remoteAddress;

    public function __construct(string $remoteAddress)
    {
        $this->remoteAddress = $remoteAddress;
    }
}
