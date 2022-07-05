<?php


namespace App\Common\KeyValueLists;

use App\Models\Masters\OrderStateTransition;
use App\Models\Masters\OrderStatus;

/**
 * Class OrderStatusList
 * @package App\Common\KeyValueLists
 * @author k.yamamoto@balocco.info
 */
class OrderStatusList extends KeyValueList
{
    /** @var OrderStatus $orderStatus */
    protected $orderStatus;
    /** @var OrderStateTransition */
    protected $orderStateTransition;

    /**
     * OrderStatusList constructor.
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->orderStatus = app(OrderStatus::class);
        $this->orderStateTransition = app(OrderStateTransition::class);
        parent::__construct($items);

    }

    public function definition(): array
    {
        return $this->orderStatus->getKeyValueList()->toArray();
    }

    /**
     * 引数のステータスに対して、遷移が許可されている遷移先ステータスリスト
     * @param $currentStatus
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function getPermittedList($currentStatus)
    {
        return $this->orderStateTransition->getPermitted($currentStatus)->pluck("toStatus.name",
            "toStatus.id")->toArray();
    }

}