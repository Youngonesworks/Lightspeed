<?php

namespace YoungOnes\Lightspeed\Server\Listeners;

use Illuminate\Support\Facades\Log;
use YoungOnes\Lightspeed\Log\LogLevel;
use YoungOnes\Lightspeed\Server\Events\ClosedConnection as Event;

class ClosedConnection
{
    public function handle(Event $event)
    {
        if (config('lightspeed_server.log_level') === LogLevel::TRACE) {
            Log::channel('stderr')->debug('Closed connection.');
        }
    }
}
