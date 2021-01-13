<?php


namespace YoungOnes\Lightspeed\Contracts\Payload;


interface ResponsePayloadContract
{
    public static function fromEncodedData($data): self;

    public function toArray(): array;

    public function getEncodedData();
}