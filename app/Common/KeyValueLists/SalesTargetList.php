<?php

namespace App\Common\KeyValueLists;


use App\Models\Masters\SalesTarget;

/**
 * Class SalesTargetList
 * @package App\Common\KeyValueLists
 * @author k.yamamoto@balocco.info
 */
class SalesTargetList extends KeyValueList
{
    /** @var SalesTarget $model */
    protected $model;

    /**
     * SalesTargetList constructor.
     * @param SalesTarget $model
     */
    public function __construct($items = [])
    {
        $this->model = app(SalesTarget::class);
        parent::__construct($items);
    }


    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }


}