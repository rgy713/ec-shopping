<?php

namespace App\Models;

use App\Models\Interfaces\KeyValueListModelInterface;
use App\Models\Traits\DefaultKeyValueListTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AdvertisingMedium extends Model implements KeyValueListModelInterface
{
    use DefaultKeyValueListTrait;

    /**
     * 広告媒体リストを返す
     * @see DefaultKeyValueListTrait
     * @return \Illuminate\Support\Collection
     * @author k.yamamoto@balocco.info
     */
    static public function getKeyValueList()
    {
        return Cache::rememberForever(self::getColumnNameOfListCacheKey(),function () {
            $result=collect();
            $tmpArray=[];
            $data=self::orderBy("code")->get();
            foreach ($data as $item){
                $tmpArray[$item->code]=$item->code.'('.$item->name.')';
            }
            return collect($tmpArray);
        });

    }


}
