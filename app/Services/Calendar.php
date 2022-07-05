<?php


namespace App\Services;


use Carbon\Carbon;

interface Calendar
{

    /**
     * 休日かどうかを判定する
     * @param Carbon $date
     * @return mixed
     * @author k.yamamoto@balocco.info
     */
    public function isHoliday(Carbon $date);

    /**
     * 基準日$fromから n営業日後の日付のCarbonオブジェクトを返す
     * @param int $n 何営業日後か
     * @param Carbon $from 基準日のCarbonオブジェクト
     * @return Carbon
     * @author k.yamamoto@balocco.info
     */
    public function businessDay(int $n,Carbon $from);

    /**
     * 翌営業日の日付のCarbonオブジェクトを返す
     * @param Carbon|null $from
     * @return Carbon
     * @author k.yamamoto@balocco.info
     */
    public function nextBusinessDay(Carbon $from=null);

}