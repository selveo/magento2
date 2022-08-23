<?php

namespace Selveo\MagentoTwoIntegration;

use Psr\Http\Message\RequestInterface;

interface SignerInterface
{
	/**
	 * @param RequestInterface $request 
	 * @return RequestInterface
	 */
	public function sign(RequestInterface $request) : RequestInterface;
}
