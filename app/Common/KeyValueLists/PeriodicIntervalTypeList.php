<?php

namespace App\Common\KeyValueLists;

use App\Models\PeriodicIntervalType;

/**
 * Class PeriodicIntervalTypeList
 * @package App\Common\KeyValueLists
 */
class PeriodicIntervalTypeList extends KeyValueList
{
    /** @var PeriodicIntervalType $model */
    protected $model;

    /**
     * PaymentList constructor.
     * @param PeriodicIntervalType $model
     */
    public function __construct(PeriodicIntervalType $model)
    {
        $this->model = $model;
        parent::__construct($this->definition());
    }

    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }
}