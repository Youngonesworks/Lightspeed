<?php


namespace YoungOnes\Lightspeed\Listeners;


use Illuminate\Support\Facades\Log;
use YoungOnes\Lightspeed\Events\ConnectionClosed as Event;
use YoungOnes\Lightspeed\Log\LogLevel;

class ConnectionClosed
{
    public function handle(Event $event)
    {
        if (config('lightspeed_server.log_level') === LogLevel::TRACE) {
            Log::channel('stderr')->debug('Connection closed.');
        }
    }
}
