<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Exceptions;

use Exception;
use Throwable;

class NotWriteableConnectionException extends Exception
{
    public function __construct(?string $message = null, ?Throwable $previous = null)
    {
        parent::__construct($message ?? 'Connection is read only.', 1, $previous);
    }
}
