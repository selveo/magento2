<?php

namespace Selveo\MagentoTwoIntegration\Observer;

use Magento\Catalog\Model\AbstractModel;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Selveo\MagentoTwoIntegration\NotifierInterface;

class OnProductSave implements ObserverInterface
{
	protected $logger;
	protected $notifier;

	public function __construct(LoggerInterface $logger, NotifierInterface $notifier)
	{
		$this->logger = $logger;
		$this->notifier = $notifier;
	}

	/** @return void */
	public function execute(Observer $observer)
	{
		$this->notifier->notifySaved($observer->getEvent()->getProduct());
	}
}
