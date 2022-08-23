<?php

namespace Selveo\MagentoTwoIntegration\Webhook;

use InvalidArgumentException;
use Magento\Catalog\Model\AbstractModel as Product;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order;

class PayloadBuilder
{
    protected string $eventName;
    protected ?string $modelName = null;
    protected ?string $modelId = null;


    public function __construct(string $eventName)
    {
        $this->eventName = $eventName;
    }

    public static function withEvent(string $eventName): self
    {
        return new static($eventName);
    }

    /**
     * @param mixed $model 
     * @return PayloadBuilder 
     * @throws InvalidArgumentException 
     */
    public function withModel($model): self
    {
        if (!is_object($model)) {
            throw new InvalidArgumentException(sprintf("type %s must be an object", gettype($model)));
        }

        if ($model instanceof Product) {
            $this->withModelNameAndID("product", $model->getEntityId());
            return $this;
        }

        if ($model instanceof Order || $model instanceof OrderInterface) {
            $this->withModelNameAndID("order", $model->getEntityId());
            return $this;
        }

        throw new InvalidArgumentException(sprintf("model %s is not a valid webhook observable", get_class($model)));
    }

    protected function withModelNameAndID(string $modelName, string $modelId): self
    {
        $this->modelName = $modelName;
        $this->modelId = $modelId;

        return $this;
    }

    public function build(): Payload
    {
        $payload = ['event' => $this->eventName];

        if (!is_null($name = $this->modelName)) {
            $payload['event'] = $name . "." . $this->eventName;
        }

        if (!is_null($id = $this->modelId)) {
            $payload['id'] = $id;
        }

        return new Payload($payload);
    }
}
