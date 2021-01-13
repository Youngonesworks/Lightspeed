<?php


namespace YoungOnes\Lightspeed\Listeners;


use Illuminate\Support\Facades\Log;
use YoungOnes\Lightspeed\Events\ConnectionEnded as Event;
use YoungOnes\Lightspeed\Log\LogLevel;

class ConnectionEnded
{
    public function handle(Event $event)
    {
        if (config('lightspeed_server.log_level') === LogLevel::TRACE) {
            Log::channel('stderr')->debug('Connection ended.');
        }
    }
}
