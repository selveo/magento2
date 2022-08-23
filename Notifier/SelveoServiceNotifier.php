<?php

namespace Selveo\MagentoTwoIntegration\Notifier;

use Magento\Catalog\Model\AbstractModel;
use Magento\Catalog\Model\Product;
use Magento\Sales\Api\Data\OrderInterface;
use Psr\Log\LoggerInterface;
use Selveo\MagentoTwoIntegration\ClientInterface;
use Selveo\MagentoTwoIntegration\NotifierInterface;

class SelveoServiceNotifier implements NotifierInterface
{
	protected $logger;
	protected $client;

	public function __construct(LoggerInterface $logger, ClientInterface $client)
	{
		$this->logger = $logger;
		$this->client = $client;
	}

	public function notifySaved(AbstractModel $model)
	{
		switch (true) {
			case $model instanceof Product:
				$this->client->notifyProductSaved($model);
				break;
		}
	}

	public function notifyPlacedOrUpdated(OrderInterface $order)
	{
		$this->client->notifyOrderPlacedOrUpdated($order);
	}
}
