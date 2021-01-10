<?php

namespace YoungOnes\Lightspeed\Requests;

class Request extends \GuzzleHttp\Psr7\Request
{
    private string $socketUri;

    public function __construct(string $socketUri, $method, $uri, array $headers = [], $body = null, $version = '1.1')
    {
//        dd($headers);
        $this->socketUri = $socketUri;
        parent::__construct($method, $uri, $headers, $body, $version);
    }

    public function getSocketUri(): string
    {
        return $this->socketUri;
    }

}
