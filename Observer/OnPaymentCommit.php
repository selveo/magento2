<?php

namespace Selveo\MagentoTwoIntegration\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Payment;
use Psr\Log\LoggerInterface;
use Selveo\MagentoTwoIntegration\NotifierInterface;

class OnPaymentCommit implements ObserverInterface
{
	protected $notifier;
	protected $logger;

	public function __construct(LoggerInterface $logger, NotifierInterface $notifier)
	{
		$this->notifier = $notifier;
		$this->logger = $logger;
	}

	/** @return void */
	public function execute(Observer $observer)
	{
		/** @var Payment */
		$payment = $observer->getEvent()->getPayment();

		$this->logger->critical('place order - only when has last_trans_id!', [$payment->getData()]);
		
		$this->notifier->notifyPlacedOrUpdated(
			$observer->getEvent()->getPayment()->getOrder()
		);
	}
}
