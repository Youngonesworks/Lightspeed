<?php


namespace YoungOnes\Lightspeed\Requests;


use CBOR\CBOREncoder;
use Illuminate\Support\Traits\Macroable;
use YoungOnes\Lightspeed\Payload\PayloadFactory;

class PendingRequest
{
    private string $socketUri;
    private ?string $encodedData;

    public function __construct(Request $request)
    {
        $data = PayloadFactory::createFromRequest($request);
//        dd(CBOREncoder::encode($data));
        $this->socketUri = $request->getSocketUri();
        $this->encodedData = CBOREncoder::encode($data);
    }

    public function getSocketUri(): string
    {
        return $this->socketUri;
    }

    public function getEncodedData(): ?string
    {
        return $this->encodedData;
    }
}
