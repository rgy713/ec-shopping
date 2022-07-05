<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/22/2019
 * Time: 10:24 PM
 */

namespace App\Services\Batch;

use Yasumi;
use App\Models\HolidaySetting;
use Carbon\Carbon;

class RegisterHolidaysService
{
    public function run($target_year=null): int
    {
        if (!isset($target_year))
            $target_year = Carbon::now()->year + 1;
        else
            $target_year = Carbon::createFromFormat("Y", $target_year)->year;

        $model = app(HolidaySetting::class);

        $count = $model->whereYear("date", "".$target_year)->count();
        if ($count > 0)
            return -1;

        $holiday_list = [];

        $saturday = Carbon::createFromFormat("Y", $target_year)->firstOfYear(6);

        do {
            $holiday_list[] = ["name" => "土曜休日", "date" => $saturday->format("Y-m-d")];
            $saturday = $saturday->addDays(7);
        } while ($saturday->year == $target_year);

        $sunday = Carbon::createFromFormat("Y", $target_year)->firstOfYear(0);

        do {
            $holiday_list[] = ["name" => "日曜休日", "date" => $sunday->format("Y-m-d")];
            $sunday = $sunday->addDays(7);
        } while ($sunday->year == $target_year);


        $holidays = Yasumi\Yasumi::create('Japan', $target_year, 'ja_JP');
        foreach ($holidays->getHolidayNames() as $name) {
            $holiday = $holidays->getHoliday($name);
            $holiday_list[] = ["name" => $holiday->getName(), "date" => $holiday->format("Y-m-d")];
        }

        foreach ($holiday_list as $holiday) {
            $one = $model->where('date', $holiday["date"])->first();
            if(!isset($one))
                $one=new HolidaySetting();
            $one->name = $holiday["name"];
            $one->date = $holiday["date"];
            $one->save();
        }

        return 0;
    }
}