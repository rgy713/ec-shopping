<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Class TaxSettings
 * @package App\Models
 * @author k.yamamoto@balocco.info
 */
class TaxSettings extends Model
{

    protected static function boot()
    {
        parent::boot();

        static::saved(self::savedListener());
    }

    private static function savedListener()
    {
        return function () {
            self::clearCache();
        };
    }

    private static function clearCache()
    {
        Cache::forget(self::class.'getAllSettings');
    }


    /**
     * 全ての税率設定を返す（キャッシュあり）
     * @return TaxSettings[]
     * @author k.yamamoto@balocco.info
     */
    public function getAllSettings(){
        $cacheKey = self::class.'getAllSettings';
        return Cache::get($cacheKey, function () use ($cacheKey) {
            $all=$this->orderBy('activated_from','DESC')->get();
            Cache::forever($cacheKey, $all);
            return $all;
        });
    }
}
