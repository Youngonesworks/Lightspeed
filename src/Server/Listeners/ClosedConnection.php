<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Server\Listeners;

use Illuminate\Support\Facades\Log;
use YoungOnes\Lightspeed\Log\LogLevel;
use YoungOnes\Lightspeed\Server\Events\ClosedConnection as Event;

class ClosedConnection
{
    public function handle(Event $event): void
    {
        if (config('lightspeed_server.log_level') !== LogLevel::TRACE) {
            return;
        }

        Log::channel('stderr')->debug('Closed connection.');
    }
}
