<?php


namespace App\Common\KeyValueLists;

use App\Models\Masters\Prefecture;

class PrefectureList extends KeyValueList
{

    /** @var Prefecture $model */
    protected $model;

    /**
     * PrefectureList constructor.
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->model = app(Prefecture::class);
        parent::__construct($items);
    }

    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }

}