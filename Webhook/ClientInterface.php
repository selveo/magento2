<?php

namespace Selveo\MagentoTwoIntegration\Webhook;

interface ClientInterface
{
	const WEBHOOK_BASE_URL = "https://magento2.selveoapps.com";

    public function send(Payload $payload);
}
