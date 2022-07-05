<?php

use Illuminate\Database\Seeder;
use App\Models\HolidaySetting;

class Init2019HolidaySettingsSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data=[
            ["2019-01-05","土曜休日"],
            ["2019-01-12","土曜休日"],
            ["2019-01-19","土曜休日"],
            ["2019-01-26","土曜休日"],
            ["2019-02-02","土曜休日"],
            ["2019-02-09","土曜休日"],
            ["2019-02-16","土曜休日"],
            ["2019-02-23","土曜休日"],
            ["2019-03-02","土曜休日"],
            ["2019-03-09","土曜休日"],
            ["2019-03-16","土曜休日"],
            ["2019-03-23","土曜休日"],
            ["2019-03-30","土曜休日"],
            ["2019-04-06","土曜休日"],
            ["2019-04-13","土曜休日"],
            ["2019-04-20","土曜休日"],
            ["2019-04-27","土曜休日"],
            ["2019-05-04","土曜休日"],
            ["2019-05-11","土曜休日"],
            ["2019-05-18","土曜休日"],
            ["2019-05-25","土曜休日"],
            ["2019-06-01","土曜休日"],
            ["2019-06-08","土曜休日"],
            ["2019-06-15","土曜休日"],
            ["2019-06-22","土曜休日"],
            ["2019-06-29","土曜休日"],
            ["2019-07-06","土曜休日"],
            ["2019-07-13","土曜休日"],
            ["2019-07-20","土曜休日"],
            ["2019-07-27","土曜休日"],
            ["2019-08-03","土曜休日"],
            ["2019-08-10","土曜休日"],
            ["2019-08-17","土曜休日"],
            ["2019-08-24","土曜休日"],
            ["2019-08-31","土曜休日"],
            ["2019-09-07","土曜休日"],
            ["2019-09-14","土曜休日"],
            ["2019-09-21","土曜休日"],
            ["2019-09-28","土曜休日"],
            ["2019-10-05","土曜休日"],
            ["2019-10-12","土曜休日"],
            ["2019-10-19","土曜休日"],
            ["2019-10-26","土曜休日"],
            ["2019-11-02","土曜休日"],
            ["2019-11-09","土曜休日"],
            ["2019-11-16","土曜休日"],
            ["2019-11-23","土曜休日"],
            ["2019-11-30","土曜休日"],
            ["2019-12-07","土曜休日"],
            ["2019-12-14","土曜休日"],
            ["2019-12-21","土曜休日"],
            ["2019-12-28","土曜休日"],
            ["2019-01-06","日曜休日"],
            ["2019-01-13","日曜休日"],
            ["2019-01-20","日曜休日"],
            ["2019-01-27","日曜休日"],
            ["2019-02-03","日曜休日"],
            ["2019-02-10","日曜休日"],
            ["2019-02-17","日曜休日"],
            ["2019-02-24","日曜休日"],
            ["2019-03-03","日曜休日"],
            ["2019-03-10","日曜休日"],
            ["2019-03-17","日曜休日"],
            ["2019-03-24","日曜休日"],
            ["2019-03-31","日曜休日"],
            ["2019-04-07","日曜休日"],
            ["2019-04-14","日曜休日"],
            ["2019-04-21","日曜休日"],
            ["2019-04-28","日曜休日"],
            ["2019-05-05","日曜休日"],
            ["2019-05-12","日曜休日"],
            ["2019-05-19","日曜休日"],
            ["2019-05-26","日曜休日"],
            ["2019-06-02","日曜休日"],
            ["2019-06-09","日曜休日"],
            ["2019-06-16","日曜休日"],
            ["2019-06-23","日曜休日"],
            ["2019-06-30","日曜休日"],
            ["2019-07-07","日曜休日"],
            ["2019-07-14","日曜休日"],
            ["2019-07-21","日曜休日"],
            ["2019-07-28","日曜休日"],
            ["2019-08-04","日曜休日"],
            ["2019-08-11","日曜休日"],
            ["2019-08-18","日曜休日"],
            ["2019-08-25","日曜休日"],
            ["2019-09-01","日曜休日"],
            ["2019-09-08","日曜休日"],
            ["2019-09-15","日曜休日"],
            ["2019-09-22","日曜休日"],
            ["2019-09-29","日曜休日"],
            ["2019-10-06","日曜休日"],
            ["2019-10-13","日曜休日"],
            ["2019-10-20","日曜休日"],
            ["2019-10-27","日曜休日"],
            ["2019-11-03","日曜休日"],
            ["2019-11-10","日曜休日"],
            ["2019-11-17","日曜休日"],
            ["2019-11-24","日曜休日"],
            ["2019-12-01","日曜休日"],
            ["2019-12-08","日曜休日"],
            ["2019-12-15","日曜休日"],
            ["2019-12-22","日曜休日"],
            ["2019-12-29","日曜休日"],
            ["2019-01-01","元日"],
            ["2019-01-14","成人の日"],
            ["2019-02-11","建国記念の日"],
            ["2019-03-21","春分の日"],
            ["2019-04-29","昭和の日"],
            ["2019-05-03","憲法記念日"],
            ["2019-05-04","みどりの日"],
            ["2019-05-05","こどもの日"],
            ["2019-05-06","振替休日"],
            ["2019-07-15","海の日"],
            ["2019-08-11","山の日"],
            ["2019-08-12","振替休日"],
            ["2019-09-16","敬老の日"],
            ["2019-09-23","秋分の日"],
            ["2019-10-14","体育の日"],
            ["2019-11-03","文化の日"],
            ["2019-11-04","振替休日"],
            ["2019-11-23","勤労感謝の日"],
            ["2019-12-23","天皇誕生日"],
            ["2019-01-02","年始休業"],
            ["2019-01-03","年始休業"],
            ["2019-01-04","年始休業"],
            ["2019-12-28","年末休業"],
            ["2019-12-29","年末休業"],
            ["2019-12-30","年末休業"],
            ["2019-12-31","年末休業"],
        ];

        foreach ($data as $item){
            $model = HolidaySetting::where('date', $item[0])->first();
            if(!isset($model))
                $model=new HolidaySetting();
            $model->date=$item[0];
            $model->name=$item[1];
            $model->save();
        }
    }
}
