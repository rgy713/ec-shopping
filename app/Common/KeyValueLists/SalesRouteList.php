<?php

namespace App\Common\KeyValueLists;

use App\Models\Masters\SalesRoute;

/**
 * Class SalesRouteList
 * @package App\Common\KeyValueLists
 * @author k.yamamoto@balocco.info
 */
class SalesRouteList extends KeyValueList
{
    /** @var SalesRoute $model */
    protected $model;

    /**
     * SalesRouteList constructor.
     * @param SalesRoute $model
     */
    public function __construct($items = [])
    {

        $this->model = app(SalesRoute::class);
        parent::__construct($items);

    }

    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }

}