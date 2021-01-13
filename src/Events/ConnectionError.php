<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Throwable;

class ConnectionError
{
    use Dispatchable;

    private Throwable $exception;

    public function __construct(Throwable $exception)
    {
        $this->exception = $exception;
    }

    public function getException(): Throwable
    {
        return $this->exception;
    }
}
