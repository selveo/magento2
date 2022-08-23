<?php

namespace Selveo\MagentoTwoIntegration;

use Magento\Catalog\Model\Product;
use Magento\Sales\Api\Data\OrderInterface;

interface ClientInterface
{
	const WEBHOOK_BASE_URL = "https://magento2.selveoapps.com";

	public function notifyProductSaved(Product $product);

	public function notifyOrderPlacedOrUpdated(OrderInterface $order);
}
