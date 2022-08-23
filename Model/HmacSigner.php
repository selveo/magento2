<?php

namespace Selveo\MagentoTwoIntegration\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Integration\Model\IntegrationService;
use Magento\Integration\Model\Oauth\Consumer;
use Psr\Http\Message\RequestInterface;
use Selveo\MagentoTwoIntegration\SignerInterface;

class HmacSigner implements SignerInterface
{
	protected $configurator;
	protected $consumer;
	protected $integration;

	public function __construct(ScopeConfigInterface $configurator, Consumer $consumer, IntegrationService $integration)
	{
		$this->configurator = $configurator;
		$this->consumer = $consumer;
		$this->integration = $integration;
	}

	public function sign(RequestInterface $request) : RequestInterface
	{
		$integrationId = $this->configurator->getValue(Config::ACTIVE_INTEGRATION_ID);
		$integration = $this->integration->get($integrationId);

		$date = date(DATE_RFC3339);
		$signature = hash_hmac('sha256', $date, $integration->getData('consumer_secret'));

		return $request
			->withHeader(
				"Authorization",
				sprintf('selveo-hmac id="%s" signature="%s"', $integration->getData("consumer_key"), $signature)
			)
			->withHeader('X-TIMESTAMP', $date);
	}
}
