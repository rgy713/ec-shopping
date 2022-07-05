<?php


namespace App\Providers\Environments\Production;


use Illuminate\Support\ServiceProvider;
use PaygentModule\System\PaygentB2BModule;
use App\Environments\Interfaces\Paygent as PaygentInterface;

/**
 * 本番環境用ペイジェント関連処理のサービスプロバイダ
 * Class PaygentServiceProvider
 * @package App\Providers\Environments\Develop
 * @author k.yamamoto@balocco.info
 */
class PaygentServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * @author k.yamamoto@balocco.info
     */
    public function boot(){

    }

    /**
     * @author k.yamamoto@balocco.info
     */
    public function register(){

        $this->app->bind(PaygentB2BModule::class,function($app){
            //Paygentオブジェクトの生成
            $object = new PaygentB2BModule();

            //private属性のメンバに設定値を格納しいるため、リフレクションクラスで無理やり書き換える
            $reflectionClass = new \ReflectionClass(get_class($object));
            $property = $reflectionClass->getProperty('app');
            $property->setAccessible(true);
            $paygentPrivateApp = $property->getValue($object);

            /**
             * 設定値:modenv_properties.php のパスを上書き
             * モジュール内のクラス定義ファイルPaygentB2BModuleResources.phpにdirname(__FILE__) と書いてあるので、
             * 当該ファイル設置ディレクトリからの相対パスで書かないとmodenv_properties.phpを読んでくれません。
             */
            $paygentPrivateApp['const']['PaygentB2BModuleResources__PROPERTIES_FILE_NAME']="/../../../../../../app/Environments/Production/Payment/Paygent/modenv_properties.php";

            //設定値:ログファイルの設置パスを上書き
            $paygentPrivateApp['const']['log_realfile']=storage_path('logs/paygent.log');

            //上書きした値をset
            $property->setValue($object,$paygentPrivateApp);

            return $object;
        });

        //本番環境用のペイジェントサービスクラス
        $this->app->bind(PaygentInterface::class,\App\Environments\Production\Payment\Paygent\PaygentService::class);

    }

    /**
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function provides()
    {
        return [PaygentB2BModule::class,PaygentInterface::class];
    }

}