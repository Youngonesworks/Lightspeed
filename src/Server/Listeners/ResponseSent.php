<?php


namespace YoungOnes\Lightspeed\Server\Listeners;


use Illuminate\Support\Facades\Log;
use YoungOnes\Lightspeed\Log\LogLevel;
use YoungOnes\Lightspeed\Server\Events\ResponseSent as Event;

class ResponseSent
{
    public function handle(Event $event)
    {
        if (config('lightspeed_server.log_level') === LogLevel::TRACE) {
            Log::channel('stderr')->debug(sprintf('Responded to client %s', $event->remoteAddress));
        }
    }
}
