<?php

declare(strict_types=1);

return [
    'port' => env('LIGHTSPEED_SERVER_PORT', 9811),
    'host' => env('LIGHTSPEED_SERVER_HOST', '127.0.0.1'),
    'log_level' => env('LIGHTSPEED_LOG_LEVEL', 'TRACE'),
];
