<?php

namespace App\Common\KeyValueLists;

use App\Models\Masters\AdvertisingMediaSummaryGroup;

/**
 * Class MediaSummaryGroupList
 * @package App\Common\KeyValueLists
 * @author k.yamamoto@balocco.info
 */
class MediaSummaryGroupList extends KeyValueList
{

    /** @var AdvertisingMediaSummaryGroup $model */
    protected $model;

    /**
     * OrderStatusList constructor.
     */
    public function __construct($items = [])
    {
        $this->model = app(AdvertisingMediaSummaryGroup::class);
        parent::__construct($items);

    }

    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }

}