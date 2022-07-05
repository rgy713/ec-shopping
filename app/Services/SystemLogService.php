<?php


namespace App\Services;

use App\Models\SystemLog;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository as Cache;

/**
 * Class SystemLogService
 * system_logsテーブルの内容を取り扱うクラス
 * @package App\Services
 * @author k.yamamoto@balocco.info
 */
class SystemLogService
{
    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var SystemLog
     */
    protected $model;

    /**
     * SystemLogService constructor.
     */
    public function __construct(Cache $cache, SystemLog $model)
    {
        $this->cache = $cache;
        $this->model = $model;
    }


    /**
     * 直近48時間のログを取得する。
     * ログ内容はキャッシュに保存する。
     * @author k.yamamoto@balocco.info
     */
    public function getLast48HoursLog()
    {

        return $this->model->OfLast48Hours()->orderBy('created_at', 'DESC')->get();
    }

    /**
     * @return mixed
     * @author k.yamamoto@balocco.info
     */
    public function getToday()
    {
        $cacheKey = get_class($this) . __FUNCTION__;
        return $this->cache->remember($cacheKey, 10, function () {
            return $this->model->today()->orderBy('created_at', 'DESC')->get();
        });

    }

    /**
     * @return mixed
     * @author k.yamamoto@balocco.info
     */
    public function getYesterday()
    {
        $cacheKey = get_class($this) . __FUNCTION__;
        return $this->cache->remember($cacheKey, 120, function () {
            return $this->model->yesterday()->orderBy('created_at', 'DESC')->get();
        });

    }

    /**
     * 件数を指定し、作成日時降順でシステムログを取得する
     * @param int $num
     * @return SystemLog[]|\Illuminate\Database\Eloquent\Collection
     * @author k.yamamoto@balocco.info
     */
    public function getLatest($num=10){
        return $this->model->orderBy('created_at', 'DESC')->limit($num)->get();
    }
}