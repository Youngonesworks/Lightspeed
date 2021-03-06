<?php

declare(strict_types=1);

namespace YoungOnes\Lightspeed\Contracts\Payload;

interface ResponsePayloadContract
{
    public static function fromEncodedData($data): self;

    public function toArray(): array;

    /**
     * @return mixed
     */
    public function getEncodedData();
}
