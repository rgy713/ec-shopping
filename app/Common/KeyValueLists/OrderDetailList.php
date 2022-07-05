<?php

namespace App\Common\KeyValueLists;

use App\Models\OrderDetail;

/**
 * Class OrderDetailList
 * @package App\Common\KeyValueLists
 */
class OrderDetailList extends KeyValueList
{

    /** @var OrderDetail $model */
    protected $model;

    /**
     * PaymentList constructor.
     * @param OrderDetail $model
     */
    public function __construct(OrderDetail $model)
    {
        $this->model = $model;
        parent::__construct($this->definition());

    }

    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }

}