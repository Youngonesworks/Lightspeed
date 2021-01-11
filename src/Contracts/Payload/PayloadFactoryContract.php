<?php

namespace YoungOnes\Lightspeed\Contracts\Payload;

use Symfony\Component\HttpFoundation\Response;
use YoungOnes\Lightspeed\Requests\Request;

interface PayloadFactoryContract
{
    public static function createFromRequest(Request $request): array;

    public static function createFromResponse(Response $response): array;
}
