<?php

namespace YoungOnes\Lightspeed\Client\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DataReceived
{
    use Dispatchable, SerializesModels;

}
