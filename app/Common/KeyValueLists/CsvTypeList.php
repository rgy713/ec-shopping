<?php

namespace App\Common\KeyValueLists;

use App\Models\Masters\CsvType;


class CsvTypeList extends KeyValueList
{

    /**
     * @var CsvType
     */
    protected $model;

    /**
     * CsvTypeList constructor.
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->model = app(CsvType::class);
        parent::__construct($items);

    }

    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }

}