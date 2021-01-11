<?php


namespace YoungOnes\Lightspeed\Payload;

use Symfony\Component\HttpFoundation\Response;
use YoungOnes\Lightspeed\Contracts\Payload\PayloadFactoryContract;
use YoungOnes\Lightspeed\Requests\Request;

class PayloadFactory implements  PayloadFactoryContract
{
    public static function createFromRequest(Request $request): array
    {
        // TODO: Implement HMAC.
        return [
            'uri' => (string) $request->getUri(),
            'parameters' => json_decode($request->getBody()->getContents()),
            'method' => $request->getMethod(),
            'headers' => $request->getHeaders()
        ];
    }

    public static function createFromResponse(Response $response): array
    {
        // TODO: Implement HMAC
        // TODO: Implement
        return [];
    }
}
