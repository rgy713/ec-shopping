<?php

namespace App\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use App\Exceptions\InvalidSettingException;

/**
 * 環境による差異を吸収するためのサービス・プロバイダ
 * Class EnvironmentServiceProvider
 * @package App\Providers
 * @author k.yamamoto@balocco.info
 */
class EnvironmentServiceProvider extends ServiceProvider
{
    CONST STAGING = 'staging';
    CONST DEVELOP = 'develop';
    CONST TESTING = 'testing';
    CONST PRODUCTION = 'production';

    /** @var array $environment 環境として指定できる文字列のリスト */
    protected $environment = [self::DEVELOP, self::STAGING, self::TESTING, self::PRODUCTION];


    public function boot()
    {

        //環境リストにない環境の場合、例外発生
        if (!in_array($this->app->environment(), $this->environment, true)) {

            throw new InvalidSettingException(
                base_path(".env"),
                "APP_ENV (in .env file) must be either " . self::PRODUCTION . ',' . self::DEVELOP . ',' . self::TESTING . ',' . self::TESTING . " . "
            );
        }

        //DEVELOP,STAGINGの場合、デバッグ用にSQLをログ出力
        if ($this->app->environment() === self::DEVELOP || $this->app->environment() === self::STAGING) {
            //クエリ、パラメータを直接ログに書き込む
            \DB::listen(function ($query) {
                $sql = $query->sql;
                for ($i = 0; $i < count($query->bindings); $i++) {
                    $sql = preg_replace("/\?/", $query->bindings[$i], $sql, 1);
                }
                $myLogger = new \Illuminate\Log\Writer(new \Monolog\Logger('SQL log'));
                $myLogger->useFiles(storage_path("logs/debug-sql.log"));
                $myLogger->info($sql);
            });
        }

        //各環境専用のサービスプロバイダを登録
        $this->registerExtraProvider();
    }

    /**
     * 環境依存するプロバイダをロードする
     * @author k.yamamoto@balocco.info
     */
    protected function registerExtraProvider()
    {
        $envName = $this->app->environment();

        $extraProviders = $this->app->config->get('app.' . $envName . '-providers');

        if (!empty($extraProviders)) {
            foreach ($extraProviders as $providerName) {
                $this->app->register($providerName);
            }
        }
    }
}