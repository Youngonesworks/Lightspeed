<?php


namespace YoungOnes\Lightspeed\Server\Listeners;


use CBOR\CBOREncoder;
use Illuminate\Support\Facades\Log;
use YoungOnes\Lightspeed\Log\LogLevel;
use YoungOnes\Lightspeed\Server\Events\DataReceived as Event;

class DataReceived
{
    public function handle(Event $event)
    {
        if (config('lightspeed_server.log_level') === LogLevel::TRACE) {
            Log::channel('stderr')->debug(sprintf('Request received from client %s', $event->remoteAddress));
        }
    }
}
