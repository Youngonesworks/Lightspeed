<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Client\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DataReceived
{
    use Dispatchable;
    use SerializesModels;
}
