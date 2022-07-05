<?php

namespace App\Common\KeyValueLists;

use App\Models\Masters\MediumType;

class MediaTypeList extends KeyValueList
{
    /** @var MediumType $model */
    protected $model;

    /**
     * MediaTypeList constructor.
     * @param MediumType $model
     */
    public function __construct(MediumType $model)
    {
        $this->model = $model;
        parent::__construct($this->definition());

    }

    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }
}