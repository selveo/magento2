<?php

namespace Selveo\MagentoTwoIntegration\Webhook;

class Payload
{
    protected array $_payload;

    public function __construct(array $payload)
    {
        $this->_payload = $payload;
    }

    public function toJSON(): string
    {
        return json_encode($this->_payload);
    }
}
