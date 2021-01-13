<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Listeners;

use Illuminate\Support\Facades\Log;
use YoungOnes\Lightspeed\Events\ConnectionError as Event;

use function get_class;
use function sprintf;

use const PHP_EOL;

class ConnectionError
{
    public function handle(Event $event): void
    {
        $error = sprintf(
            'Uncaught exception "%s"([%d]%s) at %s:%s, %s%s',
            get_class(
                $event->getException()
            ),
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
