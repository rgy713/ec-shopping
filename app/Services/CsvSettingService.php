<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/12/2019
 * Time: 11:01 PM
 */

namespace App\Services;


use App\Models\Masters\CsvType;

class CsvSettingService
{
    public function getCsvType($id)
    {
        return app(CsvType::class)->find($id);
    }

    public function update($params)
    {
        $csvType = app(CsvType::class)->find($params["id"]);
        $items = $params["item"];
        $ranks = $params["rank"];
        foreach($csvType->csvOutputSettings()->get() as $one){
            if(isset($items[$one->id])){
                $one->enabled = $items[$one->id];
            }
            if(isset($ranks[$one->id])){
                $one->rank = $ranks[$one->id];
            }
            $one->save();
        }
    }
}