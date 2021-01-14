<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Server\Events;

class SendingResponse
{
    public string $remoteAddress;

    public function __construct(string $remoteAddress)
    {
        $this->remoteAddress = $remoteAddress;
    }
}
