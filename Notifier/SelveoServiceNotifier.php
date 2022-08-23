<?php

namespace Selveo\MagentoTwoIntegration\Notifier;

use InvalidArgumentException;
use Magento\Catalog\Model\AbstractModel;
use Magento\Sales\Api\Data\OrderInterface;
use Psr\Log\LoggerInterface;
use Selveo\MagentoTwoIntegration\NotifierInterface;
use Selveo\MagentoTwoIntegration\Webhook\ClientInterface;
use Selveo\MagentoTwoIntegration\Webhook\PayloadBuilder;

class SelveoServiceNotifier implements NotifierInterface
{
	protected $logger;
	protected $webhook;

	public function __construct(LoggerInterface $logger, ClientInterface $webhook)
	{
		$this->logger = $logger;
		$this->webhook = $webhook;
	}

	public function notiyOrderSaved(OrderInterface $order)
	{
		$this->notifyModelEvent("saved", $order);
	}

	public function notifySaved(AbstractModel $model)
	{
		$this->notifyModelEvent("saved", $model);
	}

	protected function notifyModelEvent(string $event, $model)
	{
		try {
			$this->webhook->send(
				PayloadBuilder::withEvent($event)
					->withModel($model)
					->build()
			);
		} catch (InvalidArgumentException $e) {
			$this->logger->warning("unable to notify about saved model", [
				'exception' => $e
			]);
			return;
		}
		
	}
}
