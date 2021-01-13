<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Server\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClosingConnection
{
    use Dispatchable;
    use SerializesModels;

    public string $remoteAddress;

    public function __construct(string $remoteAddress)
    {
        $this->remoteAddress = $remoteAddress;
    }
}
