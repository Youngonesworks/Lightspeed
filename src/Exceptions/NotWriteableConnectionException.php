<?php


namespace YoungOnes\Lightspeed\Exceptions;


use Throwable;

class NotWriteableConnectionException extends \Exception
{
    public function __construct(string $message = null, Throwable $previous = null)
    {
        parent::__construct($message ?? 'Connection is read only.', 1, $previous);
    }
}
