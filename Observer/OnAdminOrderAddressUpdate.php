<?php

namespace Selveo\MagentoTwoIntegration\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Selveo\MagentoTwoIntegration\NotifierInterface;

class OnAdminOrderAddressUpdate implements ObserverInterface
{
	protected $notifier;
	protected $orderRepository;

	public function __construct(NotifierInterface $notifier, OrderRepositoryInterface $orderRepository)
	{
		$this->notifier = $notifier;
		$this->orderRepository = $orderRepository;
	}

	/**
	 * @return void 
	 */
	public function execute(Observer $observer)
	{
		$orderId = $observer->getEvent()->getOrderId();
		$order = $this->orderRepository->get($orderId);	

		$this->notifier->notifyPlacedOrUpdated($order);
	}
}
