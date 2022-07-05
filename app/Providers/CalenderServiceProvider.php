<?php


namespace App\Providers;

use App\Services\Calendar;
use App\Services\CalendarService;
use Illuminate\Support\ServiceProvider;

/**
 * Class CalenderServiceProvider
 * @package App\Providers
 * @author k.yamamoto@balocco.info
 */
class CalenderServiceProvider extends ServiceProvider
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
        $this->app->bind(Calendar::class,CalendarService::class);
    }

    /**
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function provides()
    {
        return [Calendar::class];
    }
}