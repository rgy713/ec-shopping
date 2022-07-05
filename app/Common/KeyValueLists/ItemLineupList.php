<?php

namespace App\Common\KeyValueLists;

use App\Models\Masters\ItemLineup;

class ItemLineupList extends KeyValueList
{

    /** @var ItemLineup $model */
    protected $model;

    /**
     * ItemLineupList constructor.
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->model = app(ItemLineup::class);
        parent::__construct($items);

    }

    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }

}