<?php


namespace App\Services;


use App\Models\TaxSettings;
use Carbon\Carbon;

/**
 * Class TaxService
 * @package App\Services
 * @author k.yamamoto@balocco.info
 */
class TaxService
{

    /**
     * TaxService constructor.
     */
    public function __construct(TaxSettings $model)
    {
        $this->model=$model;

    }

    /**
     * 引数の日時時点での税率を取得する
     * @param Carbon|null $when
     * @return mixed
     * @author k.yamamoto@balocco.info
     */
    public function getRate(Carbon $when=null){
        if(is_null($when)){
            $when=Carbon::now();
        }
        //引数の基準日時より前に施行されている税率設定のうち、施行日時が最も新しい設定を取得
        $taxSetting=$this->model->getAllSettings()->where("activated_from" , "<=",$when)->sortByDesc('activated_from')->first();
        if($taxSetting){
            return $taxSetting->rate;
        }
    }

    public function getAll()
    {
        //tax_settings.activated_from 昇順
        $settings = $this->model->orderBy('activated_from')->get();
        return $settings;
    }

    public function getActivedId()
    {
        $taxSetting = app(TaxSettings::class)->where("activated_from" , "<=",Carbon::now())->orderBy('activated_from', 'desc')->first();
        if(isset($taxSetting))
            return $taxSetting->id;
        else
            return null;
    }

    /**
     * @param $params
     */
    public function delete($params)
    {
        $this->model->where('id', $params['id'])->delete();
    }

    /**
     * @param $params
     */
    public function create($params)
    {
        $model = new TaxSettings();
        $model->name="税率{$params['rate']}%設定";
        $model->rate= $params['rate'] / 100.0;
        $model->activated_from=Carbon::parse($params['activated_from_date']." ".$params['activated_from_time'])->format('Y-m-d H:i:s');
        $model->save();
    }
}