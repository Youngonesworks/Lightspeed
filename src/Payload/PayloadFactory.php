<?php


namespace YoungOnes\Lightspeed\Payload;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use YoungOnes\Lightspeed\Contracts\Payload\RequestPayloadContract;
use YoungOnes\Lightspeed\Contracts\Payload\ResponsePayloadContract;
use YoungOnes\Lightspeed\Contracts\Payload\PayloadFactoryContract;
use YoungOnes\Lightspeed\Requests\Request;

class PayloadFactory implements PayloadFactoryContract
{

    public static function createFromRequest(Request $request): RequestPayloadContract
    {
        // TODO: Implement HMAC.
        return new RequestPayload(
            $request->getSocketUri(),
            (string) $request->getUri(),
            $request->getMethod(),
            json_decode($request->getBody()->getContents(), true) ?? [],
            $request->getHeaders() ?? []
        );
    }

    public static function createFromResponse(JsonResponse $response): ResponsePayloadContract
    {
        // TODO: Implement HMAC
        $responseContent = json_decode($response->getContent(), true);

        return new ResponsePayload(
            $responseContent['original'],
            $response->headers->all(),
            $response->getStatusCode(),
            $responseContent['exception']
        );
    }
}
