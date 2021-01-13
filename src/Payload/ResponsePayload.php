<?php

namespace YoungOnes\Lightspeed\Payload;

use CBOR\CBOREncoder;
use http\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use YoungOnes\Lightspeed\Contracts\Payload\ResponsePayloadContract;
use YoungOnes\Lightspeed\Exceptions\InvalidPayloadException;

class ResponsePayload implements ResponsePayloadContract
{
    protected ParameterBag $data;
    protected HeaderBag $headers;
    protected int $statusCode;
    protected ?string $exception;

    public function __construct(array $data = [], array $headers = [], int $statusCode = Response::HTTP_OK, ?string $exception = null)
    {
        $this->data = new ParameterBag($data);
        $this->headers = new HeaderBag($headers);
        $this->statusCode = $statusCode;
        $this->exception = $exception;
    }

    public function getEncodedData()
    {
        return CBOREncoder::encode($this->toArray());
    }

    public function headers(): HeaderBag
    {
        return $this->headers;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getException(): ?string
    {
        return $this->exception;
    }

    public function setException(?string $exception): void
    {
        $this->exception = $exception;
    }

    public function toArray(): array
    {
        return [
            'data'        => $this->data->all(),
            'headers'     => $this->headers->all(),
            'status_code' => $this->statusCode,
            'exception'   => $this->exception
        ];
    }

    public static function fromEncodedData($data): ResponsePayloadContract
    {
        $data = CBOREncoder::decode($data);

        return new static($data['data'], $data['headers'], $data['status_code'], $data['exception']);
    }
}
