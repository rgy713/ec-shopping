<?php


namespace App\Services;

use App\Models\HolidaySetting;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

/**
 * カレンダーに関連する機能をもつサービス
 * Class CalendarService
 * @package App\Services
 * @author k.yamamoto@balocco.info
 */
class CalendarService implements Calendar
{
    /**
     * @var HolidaySetting
     */
    protected $holidaySetting;

    /**
     * CalendarService constructor.
     * @param $holidaySetting
     */
    public function __construct(HolidaySetting $holidaySetting)
    {
        $this->holidaySetting = $holidaySetting;
    }

    /**
     * 休日かどうかを判定する
     * @param Carbon $date
     * @return bool
     * @author k.yamamoto@balocco.info
     *
     */
    public function isHoliday(Carbon $date)
    {
        return ($this->holidaySetting->where('date', '=', $date)->count() > 0);
    }


    /**
     * 基準日$fromから n営業日後の日付のCarbonオブジェクトを返す
     * TODO:ｎが大きい値の場合に処理が遅い。実装上問題は特に起こらないだろうが、実装を変更するべきでは？
     * @param int $n 何営業日後か
     * @param Carbon $from 基準日のCarbonオブジェクト
     * @return Carbon
     * @author k.yamamoto@balocco.info
     */
    public function businessDay(int $n, Carbon $from)
    {
        $i = 0;
        while ($i < $n) {
            if (!$this->isHoliday($from->addDays(1))) {
                $i++;
            }
        }
        return $from;
    }

    /**
     * 翌営業日の日付のCarbonオブジェクトを返す
     * @param Carbon|null $from
     * @return Carbon
     * @author k.yamamoto@balocco.info
     */
    public function nextBusinessDay(Carbon $from = null)
    {
        if (is_null($from)) {
            $from = Carbon::now();
        }
        return $this->businessDay(1, $from);
    }

}