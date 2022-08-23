<?php

namespace Selveo\MagentoTwoIntegration\Api;

interface IntegrationInterface
{
	/**
	 * @param string $consumerKey 
	 * @return void
	 */
	public function configure(string $consumerKey);
}
