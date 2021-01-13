<?php


namespace YoungOnes\Lightspeed\Events;


use Illuminate\Foundation\Events\Dispatchable;

class ConnectionError
{
    use Dispatchable;

    private \Exception $exception;

    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
    }

    public function getException(): \Exception
    {
        return $this->exception;
    }
}
