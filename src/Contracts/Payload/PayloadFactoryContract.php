<?php

namespace YoungOnes\Lightspeed\Contracts\Payload;

use YoungOnes\Lightspeed\Requests\Request;

interface PayloadFactoryContract
{
    public static function createFromRequest(Request $request): array;
}
