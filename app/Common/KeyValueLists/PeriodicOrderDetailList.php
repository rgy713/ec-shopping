<?php

namespace App\Common\KeyValueLists;

use App\Models\PeriodicOrderDetail;

/**
 * Class OrderDetailList
 * @package App\Common\KeyValueLists
 */
class PeriodicOrderDetailList extends KeyValueList
{

    /** @var PeriodicOrderDetail $model */
    protected $model;

    /**
     * PaymentList constructor.
     * @param PeriodicOrderDetail $model
     */
    public function __construct(PeriodicOrderDetail $model)
    {
        $this->model = $model;
        parent::__construct($this->definition());

    }

    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }

}