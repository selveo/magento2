<?php

namespace Selveo\MagentoTwoIntegration\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Magento\Catalog\Model\Product;
use Magento\Sales\Api\Data\OrderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Selveo\MagentoTwoIntegration\ClientInterface;
use Selveo\MagentoTwoIntegration\SignerInterface;

class Guzzle extends Client implements ClientInterface
{
	protected $logger;

	protected $signer;

	public function __construct(LoggerInterface $logger, SignerInterface $signer)
	{
		parent::__construct([
			'base_uri' => ClientInterface::WEBHOOK_BASE_URL
		]);
		$this->logger = $logger;
		$this->signer = $signer;
	}

	/**
	 * @return ResponseInterface 
	 * @throws GuzzleException 
	 */
	protected function magentoWebhook(array $body = [])
	{
		$body = json_encode($body);

		$req = new Request("POST", ClientInterface::WEBHOOK_BASE_URL . "/magento/webhook", [], $body);
		$req = $req->withHeader("content-type", "application/json");

		$req = $this->signer->sign($req);

		return $this->send($req);
	}

	/**
	 * @return void 
	 */
	public function notifyProductSaved(Product $product)
	{
		$this->magentoWebhook([
			'id' => $product->getId(),
			'event' => 'product.saved'
		]);
	}

	/**
	 * @return void 
	 */
	public function notifyOrderPlacedOrUpdated(OrderInterface $order)
	{
		$this->magentoWebhook([
			'id' => $order->getEntityId(),
			'event' => 'order.placedOrUpdated'
		]);
	}
}
