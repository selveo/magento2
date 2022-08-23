<?php

namespace Selveo\MagentoTwoIntegration\Model;

use Magento\Framework\App\Cache\Type\Config;
use Selveo\MagentoTwoIntegration\Model\Config as SelveoConfig;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Phrase;
use Magento\Framework\Webapi\Exception as WebapiException;
use Magento\Integration\Model\Integration;
use Magento\Integration\Model\Oauth\Consumer;
use Selveo\MagentoTwoIntegration\Api\IntegrationInterface;

class IntegrationService implements IntegrationInterface
{
    protected $configWriter;

    protected $integration;

    protected $consumer;

    protected $cache;

    public function __construct(WriterInterface $configWriter, Integration $integration, Consumer $consumer, TypeListInterface $cache)
    {
        $this->configWriter = $configWriter;
        $this->integration = $integration;
        $this->consumer = $consumer;
        $this->cache = $cache;
    }

    /**
     * @param string $consumerKey 
     * @return void
     */
    public function configure(string $consumerKey)
    {
        $consumer = $this->consumer->loadByKey($consumerKey);

        if($consumer->isEmpty()){
            throw new WebapiException(new Phrase("Consumer with key: %1 not found", [$consumerKey]), 400);
        }

        $integration = $this->integration->loadActiveIntegrationByConsumerId($consumer->getId());

        if ($integration->isEmpty()) {
            throw new WebapiException(new Phrase("Integration for consumer key: %1 does not exist or is not active", [$consumerKey]), 400);
        }

        $this->configWriter->save(
            SelveoConfig::ACTIVE_INTEGRATION_ID,
            $integration->getId(),
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );

        $this->cache->cleanType(Config::TYPE_IDENTIFIER);
    }
}
