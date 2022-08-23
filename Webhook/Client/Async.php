<?php

namespace Selveo\MagentoTwoIntegration\Webhook\Client;

use GuzzleHttp\Psr7\Request;
use Magento\Framework\HTTP\AsyncClient\Request as AsyncClientRequest;
use Magento\Framework\HTTP\AsyncClientInterface;
use Psr\Log\LoggerInterface;
use Selveo\MagentoTwoIntegration\SignerInterface;
use Selveo\MagentoTwoIntegration\Webhook\ClientInterface as WebhookClientInterface;
use Selveo\MagentoTwoIntegration\Webhook\Payload;

class Async implements WebhookClientInterface
{
	protected LoggerInterface $logger;
	protected SignerInterface $signer;
	protected AsyncClientInterface $http;

	public function __construct(LoggerInterface $logger, SignerInterface $signer, AsyncClientInterface $http)
	{
		$this->logger = $logger;
		$this->signer = $signer;
		$this->http = $http;
	}

	public function send(Payload $payload)
	{
		$req = new Request(
			$method = "POST",
			$url = WebhookClientInterface::WEBHOOK_BASE_URL . "/magento/webhook",
			[],
			$body = $payload->toJSON()
		);

		$req = $req->withHeader("content-type", "application/json");
		try {
			$req = $this->signer->sign($req);
		}catch(\Exception $e){
			$this->logger->error("unable to sign request", [
				'exception' => $e
			]);
			return;
		}

		try {
			$this->http->request(
				new AsyncClientRequest($url, $method, $req->getHeaders(), $body)
			)->get();
		}catch(\Throwable $e){
			$this->logger->error("[SELVEO] unable to send webhook request", [
				'exception' => $e
			]);
			return;
		}
	}
}
