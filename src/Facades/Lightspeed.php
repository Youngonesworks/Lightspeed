<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Facades;

use Illuminate\Support\Facades\Facade;

class Lightspeed extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'lightspeed';
    }
}
