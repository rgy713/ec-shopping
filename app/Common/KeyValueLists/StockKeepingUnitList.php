<?php

namespace App\Common\KeyValueLists;


use App\Models\StockKeepingUnit;

/**
 * Class StockKeepingUnitList
 * @package App\Common\KeyValueLists
 * @author k.yamamoto@balocco.info
 */
class StockKeepingUnitList extends KeyValueList
{

    /** @var StockKeepingUnit $model */
    protected $model;

    /**
     * StockKeepingUnitList constructor.
     * @param StockKeepingUnit $model
     */
    public function __construct($items = [])
    {
        $this->model = app(StockKeepingUnit::class);
        parent::__construct($items);
    }


    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }

}