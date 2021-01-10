<?php

namespace YoungOnes\Lightspeed\Server\Listeners;

use Illuminate\Support\Facades\Log;
use YoungOnes\Lightspeed\Log\LogLevel;
use YoungOnes\Lightspeed\Server\Events\ConnectedToServer as Event;

class ConnectedToServer
{
    public function handle(Event $event)
    {
        if (config('lightspeed_server.log_level') === LogLevel::TRACE) {
            Log::channel('stderr')->debug(sprintf('Client with remote address %s connected.', $event->remoteAddress));
        }
    }
}
