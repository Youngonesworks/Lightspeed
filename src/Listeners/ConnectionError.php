<?php


namespace YoungOnes\Lightspeed\Listeners;


use Illuminate\Support\Facades\Log;
use YoungOnes\Lightspeed\Events\ConnectionError as Event;

class ConnectionError
{
    public function handle(Event $event)
    {
        $error = sprintf(
        'Uncaught exception "%s"([%d]%s) at %s:%s, %s%s',
        get_class(
            $event->getException()),
            $event->getException()->getCode(),
            $event->getException()->getMessage(),
            $event->getException()->getFile(),
            $event->getException()->getLine(),
            PHP_EOL,
            $event->getException()->getTraceAsString()
        );

        Log::error($error);
        Log::channel('stderr')->error($error);
    }
}
