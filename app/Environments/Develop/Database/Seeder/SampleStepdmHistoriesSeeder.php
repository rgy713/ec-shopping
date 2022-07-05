<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 4/16/2019
 * Time: 3:52 PM
 */

namespace App\Environments\Develop\Database\Seeder;

use Illuminate\Database\Seeder;
use App\Models\StepdmHistory;

class SampleStepdmHistoriesSeeder extends Seeder
{
    public function run()
    {
        $model = new StepdmHistory();
        $model->executed_timestamp="2019-01-05 10:20:00";
        $model->total_count=16;
        $model->finished_timestamp="2019-01-08 15:10:00";
        $model->save();

        $model = new StepdmHistory();
        $model->executed_timestamp="2019-03-05 09:20:00";
        $model->total_count=null;
        $model->finished_timestamp=null;
        $model->save();

    }
}