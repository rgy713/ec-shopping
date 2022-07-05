<?php

namespace App\Services;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Database\DatabaseManager as Database;

/**
 * Class SystemInfoService
 * 管理画面に表示するシステム情報を扱うクラス
 * @package App\Services
 * @author k.yamamoto@balocco.info
 */
class SystemInfoService
{
    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var Database
     */
    protected $database;

    /**
     * SystemInfoService constructor.
     * @param Cache $cache
     * @param Database $database
     */
    public function __construct(Cache $cache, Database $database)
    {
        $this->cache = $cache;
        $this->database = $database;
    }


    /**
     * @return string
     * @author k.yamamoto@balocco.info
     */
    public function phpVersion()
    {
        return phpversion();
    }

    /**
     * @return string
     * @author k.yamamoto@balocco.info
     */
    public function phpInfo()
    {
        return php_uname();
    }

    /**
     * @return string
     * @author k.yamamoto@balocco.info
     */
    public function phpLogoPath()
    {
        return "/images/app/admin/system/php.png";
    }

    /**
     * @return mixed
     * @author k.yamamoto@balocco.info
     */
    public function postgresVersion()
    {
        //キャッシュキー
        $cacheKey = get_class($this) . __FUNCTION__;

        $result = $this->cache->get($cacheKey, function () use ($cacheKey) {
            $info = $this->postgresInfo();
            $result = explode(" ", $info)[1];
            $this->cache->forever($cacheKey, $result);
            return $result;
        });

        return $result;
    }

    /**
     * @return mixed
     * @author k.yamamoto@balocco.info
     */
    public function postgresInfo()
    {
        $cacheKey = get_class($this) . __FUNCTION__;

        $result = $this->cache->get($cacheKey, function () use ($cacheKey) {
            //キャッシュがなければDBから情報を取得
            $result = $this->database->connection()->selectOne("SELECT version() as version");
            $this->cache->forever($cacheKey, $result->version);
            return $result->version;
        });
        return $result;
    }

    /**
     * @return string
     * @author k.yamamoto@balocco.info
     */
    public function postgresLogoPath()
    {
        return "/images/app/admin/system/postgres.png";
    }

    /**
     * @return string
     * @author k.yamamoto@balocco.info
     */
    public function laravelVersion()
    {
        return app()->version();
    }

    /**
     * @return string
     * @author k.yamamoto@balocco.info
     */
    public function laravelInfo()
    {
        //キャッシュがあれば返す
        if ($result = $this->cache->get(get_class($this) . __FUNCTION__)) {
            return $result;
        }

        $result = implode(" ", [
            "APP_NAME:" . config("app.name"),
            "APP_ENV:" . config("app.env"),
            "TIMEZONE:" . config("app.timezone"),
            "LOCALE:" . config("app.locale"),
            "DEBUG:" . config("app.debug")
        ]);
        //キャッシュに保存
        $this->cache->forever(get_class($this) . __FUNCTION__, $result);
        //結果を返す
        return $result;
    }

    /**
     * @return string
     * @author k.yamamoto@balocco.info
     */
    public function laravelLogoPAth()
    {
        return "/images/app/admin/system/laravel.png";
    }
}