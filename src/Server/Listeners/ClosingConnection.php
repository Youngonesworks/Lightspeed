<?php

namespace YoungOnes\Lightspeed\Server\Listeners;

use Illuminate\Support\Facades\Log;
use YoungOnes\Lightspeed\Log\LogLevel;
use YoungOnes\Lightspeed\Server\Events\ClosingConnection as Event;

class ClosingConnection
{
    public function handle(Event $event)
    {
        if (config('lightspeed_server.log_level') === LogLevel::TRACE) {
            Log::channel('stderr')->debug(sprintf('Closing connection to client %s', $event->remoteAddress));
        }
    }
}
