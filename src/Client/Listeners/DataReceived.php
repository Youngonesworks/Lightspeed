<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Client\Listeners;

use Illuminate\Support\Facades\Log;
use YoungOnes\Lightspeed\Log\LogLevel;

class DataReceived
{
    public function handle(): void
    {
        if (config('lightspeed_server.log_level') !== LogLevel::TRACE) {
            return;
        }

        Log::channel('stderr')->debug('Received response from server. Closing Connection.');
    }
}
