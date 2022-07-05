<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{

    /**
     * data 属性のミューテタ
     * @param $value
     * @author k.yamamoto@balocco.info
     */
    public function setDataAttribute($value)
    {

        if (is_array($value)) {
            $value = json_encode($value);
        }
        $this->attributes['data'] = $value;
    }


    /**
     * 過去48時間
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfLast48Hours($query)
    {
        $carbon = Carbon::now()->subHour(48);
        return $query->where("created_at", ">", $carbon);
    }

    /**
     * 「今日」のログを取得する。
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     * @author k.yamamoto@balocco.info
     */
    public function scopeToday($query)
    {
        return $query->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()]);
    }

    /**
     * 「昨日」のログを取得する。
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     * @author k.yamamoto@balocco.info
     */
    public function scopeYesterday($query)
    {
        return $query->whereBetween('created_at', [Carbon::yesterday(), Carbon::today()]);
    }

    /**
     * @return \Illuminate\Support\Collection
     * @author k.yamamoto@balocco.info
     */
    public function getDataAttribute()
    {
        return collect(json_decode($this->attributes['data']));
    }


}
