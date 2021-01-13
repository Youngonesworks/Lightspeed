<?php


namespace YoungOnes\Lightspeed\Payload;

use CBOR\CBOREncoder;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use YoungOnes\Lightspeed\Contracts\Payload\RequestPayloadContract;
use YoungOnes\Lightspeed\Contracts\Payload\ResponsePayloadContract;

class RequestPayload implements RequestPayloadContract
{
    protected string $to;
    protected string $uri;
    protected string $method;
    protected ParameterBag $parameters;
    protected HeaderBag $headers;

    public function __construct(string $to, string $uri, string $method = 'LIGHTSPEED', array $parameters = [], array $headers = [])
    {
        $this->to = $to;
        $this->uri = $uri;
        $this->method = $method;
        $this->parameters = new ParameterBag($parameters);
        $this->headers = new HeaderBag($headers);
    }

    public static function fromEncodedData($data): self
    {
        $data = CBOREncoder::decode($data);

        return new static($data['to'], $data['uri'], $data['method'], $data['parameters'], $data['headers']);
    }

    public function toArray(): array
    {
        return [
            'to' => $this->to,
            'uri' => $this->uri,
            'method' => $this->method,
            'parameters' => $this->parameters->all(),
            'headers' => $this->headers->all()
        ];
    }

    public function getEncodedData()
    {
        return CBOREncoder::encode($this->toArray());
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function parameters(): ParameterBag
    {
        return $this->parameters;
    }

    public function headers(): HeaderBag
    {
        return $this->headers;
    }

    public function getReceivingAddress(): string
    {
        return $this->to;
    }

}
