<?php


namespace YoungOnes\Lightspeed\Payload;

use YoungOnes\Lightspeed\Contracts\Payload\PayloadFactoryContract;
use YoungOnes\Lightspeed\Requests\Request;

class PayloadFactory implements  PayloadFactoryContract
{
    public static function createFromRequest(Request $request): array
    {
        // TODO: Implement HMAC.
        return [
            'uri' => (string) $request->getUri(),
            'data' => json_decode($request->getBody()->getContents()),
            'method' => $request->getMethod(),
            'headers' => $request->getHeaders()
        ];
    }
}
