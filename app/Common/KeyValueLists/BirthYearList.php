<?php

namespace App\Common\KeyValueLists;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Cache;

/**
 * 誕生年リスト
 * 実行時点で90歳から10歳の範囲
 * Class BirthYearList
 * @package App\Common\KeyValueLists
 * @author k.yamamoto@balocco.info
 */
class BirthYearList extends KeyValueList
{
    public function definition(): array
    {
        //キャッシュ期間は7日に設定（特に意図なし、12/31→1/1のタイミングで反映が最大7日遅れるが、）
        return Cache::remember('App.Common.KeyValueLists.BirthYearList',1440, function () {

            $birthYearList=[];

            //終了年を10年後としたが、特に根拠・意図はない。
            $end=new Carbon("10 years ago");

            //誕生日不明のユーザーを1900年1月1日としているらしいデータが存在するため、開始年は1900年とする（旧システム踏襲）
            //どう考えてもご存命でないのだが、そのあたりは気にしないことにする。
            $period = CarbonPeriod::create('1900-01-01', '1 year', $end);
            foreach ($period as $key => $date) {
                $birthYearList[$date->format('Y')]=$date->format('Y');
            }
            return $birthYearList;
        });
    }

}