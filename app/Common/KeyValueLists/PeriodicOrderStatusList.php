<?php


namespace App\Common\KeyValueLists;


use App\Models\Masters\PeriodicOrderStatus;

/**
 * Class PeriodicOrderStatusList
 * @package App\Common\KeyValueLists
 * @author k.yamamoto@balocco.info
 */
class PeriodicOrderStatusList extends KeyValueList
{

    /** @var PeriodicOrderStatus $model */
    protected $model;

    /**
     * PeriodicOrderStatusList constructor.
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->model = app(PeriodicOrderStatus::class);
        parent::__construct($items);

    }

    public function definition(): array
    {
//        return $this->model->getKeyValueList()->toArray();
        return [
            0 => "正常",
            1 => "失敗",
        ];
    }

}