<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Requests;

class Request extends \GuzzleHttp\Psr7\Request
{
    public const METHOD = 'LIGHTSPEED';
    private string $socketUri;

    public function __construct(string $socketUri, $uri, array $headers = [], $body = null, $version = '1.1')
    {
        parent::__construct(self::METHOD, $uri, $headers, $body, $version);
        $this->socketUri = $socketUri;
    }

    public function getSocketUri(): string
    {
        return $this->socketUri;
    }
}
