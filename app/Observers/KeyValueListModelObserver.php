<?php


namespace App\Observers;


use Illuminate\Support\Facades\Cache;

/**
 * マスタ系モデルに適用する共通のオブザーバ。
 * KeyValueListModelInterface を実装しているモデルに対してのみ適用可能。
 * Class KeyValueListModelObserver
 * @package App\Observers
 * @author k.yamamoto@balocco.info
 */
class KeyValueListModelObserver
{
    public function saved($model){
        Cache::forget($model->getColumnNameOfListCacheKey());
    }

    public function deleted ($model){
        Cache::forget($model->getColumnNameOfListCacheKey());
    }

    public function restored ($model){
        Cache::forget($model->getColumnNameOfListCacheKey());
    }

}