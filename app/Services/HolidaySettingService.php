<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 4/8/2019
 * Time: 10:25 PM
 */

namespace App\Services;

use Carbon\Carbon;
use App\Models\HolidaySetting;

class HolidaySettingService
{
    public function getHolidays()
    {
        //holiday_settingsのデータを表示する。
        //・日付昇順
        //・画面を表示した時点の「現在年1月1日」以降のデータを表示対象とする
        $currentYear = Carbon::now()->setTimezone("UTC")->year;
        $holidays = HolidaySetting::whereYear('date', '>=' ,$currentYear)
                    ->orderby('date')
                    ->get();
        return $holidays;
    }

    /**
     * @param $params
     */
    public function update($params)
    {
        $ids = $params['ids'];
        foreach ($ids as $id)
        {
            $holiday = HolidaySetting::find($id);
            if($holiday->date == $params['date'][$id] && $holiday->name == $params['name'][$id])
                continue;
            $holiday->date = $params['date'][$id];
            $holiday->name = $params['name'][$id];
            $holiday->save();
        }
    }

    /**
     * @param $params
     */
    public function create($params)
    {
        $holiday = new HolidaySetting();
        $holiday->date=$params['date'];
        $holiday->name=$params['holiday_name'];
        $holiday->save();
    }

    /**
     * @param $params
     */
    public function delete($params)
    {
        $ids = $params['delete_ids'];
        HolidaySetting::whereIn('id', $ids)->delete();
    }
}