<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Contracts\Payload;

use Illuminate\Http\JsonResponse;
use YoungOnes\Lightspeed\Requests\Request;

interface PayloadFactoryContract
{
    public static function createFromRequest(Request $request): RequestPayloadContract;

    public static function createFromResponse(JsonResponse $response): ResponsePayloadContract;
}
