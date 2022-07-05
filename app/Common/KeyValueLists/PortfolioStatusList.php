<?php

namespace App\Common\KeyValueLists;

use App\Models\Masters\PfmStatus;

/**
 * Class PortfolioStatusList
 * @package App\Common\KeyValueLists
 * @author k.yamamoto@balocco.info
 */
class PortfolioStatusList extends KeyValueList
{
    /** @var PfmStatus $model */
    protected $model;

    /**
     * PortfolioStatusList constructor.
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->model = app(PfmStatus::class);
        parent::__construct($items);

    }

    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }

}