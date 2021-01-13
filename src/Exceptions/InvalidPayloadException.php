<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Exceptions;

use Exception;

use function json_last_error;

class InvalidPayloadException extends Exception
{
    public function __construct($message = null)
    {
        parent::__construct($message ?: json_last_error());
    }
}
