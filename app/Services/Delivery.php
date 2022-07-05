<?php


namespace App\Services;


use Carbon\Carbon;
use App\Models\DeliveryTime;
use App\Exceptions\InvalidDataStateException;
use App\Models\Delivery as DeliveryModel;

interface Delivery
{
    /**
     * 配送希望日のリストを配列で返す
     * @param Carbon $from
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function getDeliveryRequestDateList(Carbon $from);

    /**
     * 配送情報をID指定で取得
     * @param $deliveryId
     * @return DeliveryModel|\Illuminate\Database\Eloquent\Model|null
     * @author k.yamamoto@balocco.info
     */
    public function find($deliveryId);

    /**
     * 配送時間情報を配送ID指定で取得
     * @param $deliveryId
     * @return \App\Models\DeliveryTime[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     * @author k.yamamoto@balocco.info
     */
    public function findDeliveryTimes($deliveryId);

    /**
     * $from時点の最短のお届け希望日を返す
     * @param Carbon $from
     * @return mixed
     * @author k.yamamoto@balocco.info
     */
    public function shortestDeliveryRequestDate(Carbon $from);


    /**
     * $productDeliveryTypeIdの商品を購入時に選択可能な配送方法を返す
     * @param $productDeliveryTypeId
     * @return DeliveryModel|\Illuminate\Database\Eloquent\Model
     * @throws InvalidDataStateException
     * @author k.yamamoto@balocco.info
     */
    public function findDeliveryByProductDeliveryType($productDeliveryTypeId);

    /**
     * 配送先の都道府県リードタイム情報を参照し、「基準日」に発送した場合の到着予定日を返す。
     * @param Carbon $from 計算の基準日時
     * @param mixed $prefectureId 配送先の都道府県ID
     * @param mixed $deliveryId 配送業者ID
     * @return Carbon
     * @author k.yamamoto@balocco.info
     */
    public function getEstimatedArrivalDate(Carbon $from, $prefectureId, $deliveryId): Carbon;

    /**
     * 時刻と配送IDに対応する配送時刻オブジェクトを取得する。
     * @param Carbon $time
     * @param $deliveryId
     * @return DeliveryTime
     * @author k.yamamoto@balocco.info
     */
    public function determineDeliveryTime(Carbon $time, $deliveryId): ?DeliveryTime;

    /**
     * @param $deliveryId
     * @param $prefectureId
     * @return DeliveryLeadtime[]|\Illuminate\Database\Eloquent\Collection
     * @author k.yamamoto@balocco.info
     */
    public function getLeadTime($prefectureId, $deliveryId);

    /**
     * ユーザー画面表示している配送業者リストを取得する
     * @return DeliveryModel
     * @author k.yamamoto@balocco.info
     */
    public function getUserVisibleDeliveryList();

    /**
     * 配送方法ID、配送先都道府県IDを指定して配送手数料を取得する。
     * @param $deliveryId
     * @param $prefectureId
     * @return int
     * @author k.yamamoto@balocco.info
     */
    public function getDeliveryFee($deliveryId, $prefectureId): int;
}