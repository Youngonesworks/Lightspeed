<?php


namespace YoungOnes\Lightspeed\Exceptions;


class InvalidPayloadException extends \Exception
{
    public function __construct($message = null)
    {
        parent::__construct($message ?: json_last_error());
    }
}
