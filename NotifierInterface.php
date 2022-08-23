<?php

namespace Selveo\MagentoTwoIntegration;

use \Magento\Catalog\Model\AbstractModel;
use Magento\Sales\Api\Data\OrderInterface;

interface NotifierInterface
{
	public function notifySaved(AbstractModel $model);

	public function notiyOrderSaved(OrderInterface $order);
}
