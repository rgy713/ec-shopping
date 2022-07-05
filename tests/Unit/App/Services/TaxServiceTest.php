<?php


namespace Tests\Unit\App\Services;


use App\Services\TaxService;
use Carbon\Carbon;
use Tests\TestCase;

class TaxServiceTest extends TestCase
{
    /**
     * @dataProvider getRateDataProvider
     * @author k.yamamoto@balocco.info
     */
    public function testGetRate($when,$expected){
        $service = app(TaxService::class);
        $this->assertEquals($service->getRate($when),$expected);
    }

    /**
     * @author k.yamamoto@balocco.info
     */
    public function getRateDataProvider(){
        return [
            [Carbon::create(1997,4,1,0,0,0),0.05],
            [Carbon::create(1997,3,31,23,59,59),null],
            [Carbon::create(2014,3,31,23,59,59),0.05],
            [Carbon::create(2014,4,1,0,0,0),0.08],
            [Carbon::create(2019,9,30,23,59,59),0.08],
            [Carbon::create(2019,10,1,00,0,0),0.10],
        ];
    }

}