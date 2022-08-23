<?php

namespace Selveo\MagentoTwoIntegration\Model\Config\Source\Active;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\DataObject;
use Magento\Integration\Model\Integration as ModelIntegration;
use Magento\Integration\Model\ResourceModel\Integration\Collection;

class Integration implements OptionSourceInterface
{
	protected $integrations;

	/**
	 * @var DataObject[]
	 */
	protected $activeIntegrations;

	public function __construct(Collection $integrations)
	{
		$this->integrations = $integrations;
		$this->activeIntegrations = $integrations
			->addFilter(ModelIntegration::STATUS, ModelIntegration::STATUS_ACTIVE)
			->getItems();
	}

	public function toOptionArray()
	{
		$integrations = [
			[
				'value' => '', 'label' => '-- No integration chosen --'
			]
		];
		
		foreach ($this->activeIntegrations as $integration) {
			$integrations[] = [
				'value' => $integration->getId(), 'label' => $integration->getName()
			];
		}
		
		return $integrations;
	}
}
