<?php


namespace App\Services;


use App\Exceptions\InvalidDataStateException;
use App\Models\DeliveryFee;
use App\Models\DeliveryLeadtime;
use App\Models\DeliveryTime;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Delivery as DeliveryModel;

/**
 * 配送関連処理
 * Class DeliveryService
 * @package App\Services
 * @author k.yamamoto@balocco.info
 */
class DeliveryService implements Delivery
{
    //TODO:config に移動
    CONST SHORTEST_DELIVERY_BUSINESS_DATE = 4;
    //TODO:config に移動
    CONST SHIPPING_ON_THE_DAY_TIME_LIMIT = "12:00";
    //TODO:config に移動
    CONST DAYS_END = 12;

    /**
     * @var Calendar
     */
    protected $calenderService;

    /**
     * @var DeliveryModel
     */
    protected $deliveryModel;

    /** @var DeliveryLeadtime */
    protected $leadtimeModel;

    /** @var DeliveryTime */
    protected $deliveryTimeModel;

    /** @var DeliveryFee */
    protected $deliveryFeeModel;

    /**
     * DeliveryService constructor.
     * @param Calendar $calenderService
     */
    public function __construct(Calendar $calenderService, DeliveryModel $model, DeliveryLeadtime $leadtime)
    {
        $this->calenderService = $calenderService;
        $this->leadtimeModel = $leadtime;
        $this->deliveryModel = $model;
        $this->deliveryTimeModel = app(DeliveryTime::class);
        $this->deliveryFeeModel = app(DeliveryFee::class);
    }

    /**
     * @inheritDoc
     */
    public function getDeliveryRequestDateList(Carbon $from, $prefectureId = null, $deliveryId = null)
    {
        //初期化
        $result = [];

        $start = $this->shortestDeliveryRequestDate($from, $prefectureId, $deliveryId);

        //12日間のリストを作成する
        //TODO:定数化、またはconfigに移動
        $end = clone $start;
        $end->addDays(12);

        /** @var CarbonPeriod $period */
        $period = CarbonPeriod::create($start, "1 day", $end);

        /** @var Carbon $date */
        foreach ($period as $key => $date) {
            $str = $date->format("Ymd");
            $result[$str] = $date->format("Y-m-d (D)");
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function find($deliveryId)
    {
        return $this->deliveryModel->find($deliveryId);
    }

    /**
     * @inheritDoc
     */
    public function findDeliveryTimes($deliveryId)
    {
        //初期化
        $result = collect();

        $delivery = $this->find($deliveryId);
        if ($delivery) {
            $result = $delivery->deliveryTimes;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function shortestDeliveryRequestDate(Carbon $from, $prefectureId = null, $deliveryId = null)
    {
        //初期化
        $result = [];

        //配送方法ごとのリードタイム日数を取得
        $ShortestDeliveryBusinessDate = $this->getLeadTime($prefectureId, $deliveryId);
        if (is_null($ShortestDeliveryBusinessDate)) {
            //取得できなかった場合、デフォルト値
            $ShortestDeliveryBusinessDate = self::SHORTEST_DELIVERY_BUSINESS_DATE;
        }


        //日付に特に意味は無い（時刻部分だけ比較したいため、同じ日付であればOK）
        $a = Carbon::parse("1990-01-01" . $from->format("H:i"));
        $b = Carbon::parse("1990-01-01 " . self::SHIPPING_ON_THE_DAY_TIME_LIMIT);

        //当日発送の締切時刻を過ぎている場合、最短お届け可能日を1日後ろにずらす
        if ($a >= $b) {
            $ShortestDeliveryBusinessDate += 1;
        }

        //最短でお届け可能な日付を計算

        $start = $this->calenderService->businessDay($ShortestDeliveryBusinessDate, $from);

        return $start;
    }

    /**
     * @inheritDoc
     */
    public function findDeliveryByProductDeliveryType($productDeliveryTypeId)
    {
        try {
            $delivery = $this->deliveryModel
                ->where("product_delivery_type_id", "=", $productDeliveryTypeId)
                ->where("user_visible", "=", true)
                ->firstOrFail();
            return $delivery;
        } catch (\Exception $e) {
            //選択可能な配送方法が無い場合は例外
            throw new InvalidDataStateException("deliveries",
                "Customer has no selectable delivery_id at the time of purchase for product_delivery_type " . $productDeliveryTypeId . ".");
        }
    }

    /**
     * @inheritDoc
     */
    public function getUserVisibleDeliveryList()
    {
        return $this->deliveryModel->where("user_visible", "=", true)->orderBy("rank", "ASC");
    }

    /**
     * @inheritDoc
     */
    public function getLeadTime($prefectureId, $deliveryId)
    {
        $result = $this->leadtimeModel
            ->where("prefecture_id", "=", $prefectureId)
            ->where("delivery_id", "=", $deliveryId)
            ->first();
        if (!is_null($result)) {
            return $result->days;
        }
    }

    /**
     * @inheritDoc
     */
    public function getEstimatedArrivalDate(Carbon $from, $prefectureId, $deliveryId): Carbon
    {
        $days = $this->getLeadTime($prefectureId, $deliveryId);
        return $from->addDays($days);
    }

    /**
     * @inheritDoc
     */
    public function determineDeliveryTime(Carbon $time, $deliveryId): ?DeliveryTime
    {
        return $this->deliveryTimeModel
            ->where("delivery_id", "=", $deliveryId)
            ->where("time_range_to", ">=", $time)
            ->orderBy("time_range_from", "ASC")
            ->limit(1)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function getDeliveryFee($deliveryId, $prefectureId): int
    {
        return $this->deliveryFeeModel->where("delivery_id","=",$deliveryId)->where("prefecture_id","=",$prefectureId)->first()['fee'];
    }
}