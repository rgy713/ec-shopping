<?php


namespace App\Providers;

use App\Services\Delivery;
use App\Services\DeliveryService;
use Illuminate\Support\ServiceProvider;

/**
 * Class DeliveryServiceProvider
 * @package App\Providers
 * @author k.yamamoto@balocco.info
 */
class DeliveryServiceProvider extends ServiceProvider
{
    /**
     * プロバイダのローディングを遅延させるフラグ
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
        $this->app->bind(Delivery::class,DeliveryService::class);
    }

    /**
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function provides()
    {
        return [Delivery::class];
    }
}