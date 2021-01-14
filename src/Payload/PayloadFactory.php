<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Payload;

use Illuminate\Http\JsonResponse;
use YoungOnes\Lightspeed\Contracts\Payload\PayloadFactoryContract;
use YoungOnes\Lightspeed\Contracts\Payload\RequestPayloadContract;
use YoungOnes\Lightspeed\Contracts\Payload\ResponsePayloadContract;
use YoungOnes\Lightspeed\Requests\Request;

use function json_decode;

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
        ray($responseContent);
        return new ResponsePayload(
            $responseContent,
            $response->headers->all(),
            $response->getStatusCode(),
            $response->exception ? $response->exception->getMessage() : ""
        );
    }
}
