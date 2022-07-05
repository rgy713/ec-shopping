<?php

namespace App\Common\KeyValueLists;

use App\Models\Masters\PurchaseRoute;

class PurchaseRouteList extends KeyValueList
{

    /** @var PurchaseRoute $model */
    protected $model;

    /**
     * PurchaseRouteList constructor.
     * @param array $items
     */
    public function __construct($items=[])
    {
        $this->model = app(PurchaseRoute::class);
        parent::__construct($items);

    }

    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }


}