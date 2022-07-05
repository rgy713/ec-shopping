<?php

namespace App\Common\KeyValueLists;

use App\Models\Delivery as DeliveryModel;
use App\Services\Delivery as DeliveryService;

class DeliveryList extends KeyValueList
{
    /** @var Delivery $model */
    protected $model;

    /** @var DeliveryService */

    protected $deliveryService;

    /**
     * DeliveryList constructor.
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->model = app(DeliveryModel::class);
        $this->deliveryService = app(DeliveryService::class);
        parent::__construct($items);

    }


    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }

    /**
     * @param $deliveryId
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function getDeliveryTimeList($deliveryId, $pluck = true)
    {
        $collection = $this->deliveryService->findDeliveryTimes($deliveryId);
        if ($pluck) {
            return $collection->sortBy("rank")->pluck("delivery_time", "id")->toArray();
        }
        else {
            return $collection->sortBy("rank")->toArray();
        }
    }


    public function getUserVisibleDeliveryList()
    {
        return $this->deliveryService->getUserVisibleDeliveryList()->pluck("name", "id");
    }
}