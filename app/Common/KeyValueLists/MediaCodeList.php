<?php

namespace App\Common\KeyValueLists;

use App\Models\AdvertisingMedia;

class MediaCodeList extends KeyValueList
{
    /** @var AdvertisingMedia $model */
    protected $model;

    /**
     * OrderStatusList constructor.
     */
    public function __construct($items = [])
    {
        $this->model = app(AdvertisingMedia::class);
        parent::__construct($items);

    }


    /**
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }

}