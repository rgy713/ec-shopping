<?php


namespace App\Common\KeyValueLists;


use App\Services\Delivery;
use Carbon\Carbon;

/**
 * 「配達希望日」選択肢のリスト
 * ユーザー画面での表示、及び管理画面の受注登録時に利用
 * TODO:仕様の確認
 * Class DeliveryDateList
 * @package App\Common\KeyValueLists
 * @author k.yamamoto@balocco.info
 */
class DeliveryRequestDateList extends KeyValueList
{
    /**
     * @var Delivery
     */
    protected $deliveryService;

    /**
     * DeliveryRequestDateList constructor.
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->deliveryService = app(Delivery::class);
        parent::__construct($items);
    }


    public function definition(): array
    {
        return $this->deliveryService->getDeliveryRequestDateList(Carbon::now());
    }

    /**
     * @param $prefectureId
     * @param $deliveryId
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function getWithLeadTime($prefectureId, $deliveryId)
    {
        return $this->deliveryService->getDeliveryRequestDateList(Carbon::now(), $prefectureId, $deliveryId);
    }

}