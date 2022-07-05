<?php

namespace App\Providers;

use App\Common\CustomBlade;
use App\Models\AdvertisingMedia;
use App\Models\Delivery;
use App\Models\MailLayout;
use App\Models\Masters\AdminRole;
use App\Models\Masters\AdvertisingMediaSummaryGroup;
use App\Models\Masters\ItemLineup;
use App\Models\Masters\OrderStatus;
use App\Models\Masters\PeriodicOrderStatus;
use App\Models\Masters\PfmStatus;
use App\Models\Masters\Prefecture;
use App\Models\Masters\SalesRoute;
use App\Models\Masters\SalesTarget;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\StockKeepingUnit;
use App\Observers\KeyValueListModelObserver;
use App\Services\AdminSettingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $customBlade = new CustomBlade();
        Blade::directive('isInvalid', [$customBlade, 'isInvalid']);
        Blade::directive('datetime', [$customBlade, 'datetime']);
        Blade::directive('rateToPercent', [$customBlade, 'rateToPercent']);

        //KeyValueListをキャッシュしているモデル
        Prefecture::observe(KeyValueListModelObserver::class);
        OrderStatus::observe(KeyValueListModelObserver::class);
        AdminRole::observe(KeyValueListModelObserver::class);
        Delivery::observe(KeyValueListModelObserver::class);
        ItemLineup::observe(KeyValueListModelObserver::class);
        MailLayout::observe(KeyValueListModelObserver::class);
        AdvertisingMedia::observe(KeyValueListModelObserver::class);
        AdvertisingMediaSummaryGroup::observe(KeyValueListModelObserver::class);
        PaymentMethod::observe(KeyValueListModelObserver::class);
        PeriodicOrderStatus::observe(KeyValueListModelObserver::class);
        PfmStatus::observe(KeyValueListModelObserver::class);
        Product::observe(KeyValueListModelObserver::class);
        SalesRoute::observe(KeyValueListModelObserver::class);
        SalesTarget::observe(KeyValueListModelObserver::class);
        StockKeepingUnit::observe(KeyValueListModelObserver::class);

        // Extend Validation greater_than_field
        Validator::extend('greater_than_field', function($attribute, $value, $parameters, $validator) {
            $min_field = $parameters[0];
            $data = $validator->getData();
            $min_value = $data[$min_field];
            return $value >= $min_value;
        });

        Validator::replacer('greater_than_field', function($message, $attribute, $rule, $parameters) {
            return str_replace(':field', __('validation.attributes.'.$parameters[0]), $message);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
