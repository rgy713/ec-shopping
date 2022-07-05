<?php

namespace App\Common\KeyValueLists;

use App\Models\Masters\FixedPeriodicInterval;

class FixedPeriodicIntervalList extends KeyValueList
{
    protected $model;

    public function __construct(FixedPeriodicInterval $model)
    {
        $this->model = $model;
        parent::__construct($this->definition());
    }

    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }
}