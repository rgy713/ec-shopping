<?php


namespace Tests\Unit\App\Services\Periodic\Interval;


use App\Services\Periodic\Interval\PeriodicIntervalFactory;
use Carbon\Carbon;
use Tests\TestCase;

class PeriodicIntervalTest extends TestCase
{

    /**
     * 定期間隔種別のテスト
     * @param $periodicIntervalTypeId
     * @param $baseDate
     * @param $expected
     * @param $parameters
     * @author k.yamamoto@balocco.info
     * @dataProvider intervalTypeData
     */
    public function testPeriodicIntervalTypes($periodicIntervalTypeId,$baseDate,$expected,$parameters){
        $factory = new PeriodicIntervalFactory();
        $concreteClass = $factory->create($periodicIntervalTypeId,$parameters);
        $this->assertEquals($expected,$concreteClass->nextDeliveryDate($baseDate));
    }

    public function intervalTypeData(){
        return [
            //種別ID,起算基準日,予期される結果の日付,パラメタ配列
            [
                1,
                Carbon::create(2018,1,1,0,0,0),
                Carbon::create(2018,3,2,0,0,0),
                ['interval_days'=>60,'interval_month'=>null,'interval_date_of_month'=>null]
            ],
            [
                1,
                Carbon::create(2018,1,1,0,0,0),
                Carbon::create(2018,1,11,0,0,0),
                ['interval_days'=>10,'interval_month'=>null,'interval_date_of_month'=>null]
            ],
            [
                1,
                Carbon::create(2018,1,1,0,0,0),
                Carbon::create(2018,1,11,0,0,0),
                ['interval_days'=>10,'interval_month'=>null,'interval_date_of_month'=>null] //10
            ],

            [
                2,
                Carbon::create(2019,1,1,0,0,0),
                Carbon::create(2019,2,28,0,0,0),
                ['interval_days'=>null,'interval_month'=>1,'interval_date_of_month'=>28] //1ヶ月ごと、28日
            ],
            [
                2,
                Carbon::create(2019,3,20,0,0,0),
                Carbon::create(2019,9,1,0,0,0),
                ['interval_days'=>null,'interval_month'=>6,'interval_date_of_month'=>1] //6ヶ月ごと、1日
            ],
        ];
    }
}