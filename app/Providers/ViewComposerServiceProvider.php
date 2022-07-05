<?php

namespace App\Providers;

use App\Http\ViewComposers\Admin\SystemInfoComposer;
use App\Http\ViewComposers\Admin\SystemLogComposer;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\ViewComposers\Admin\AuthComposer as AdminAuthComposer;

/**
 * Class ViewComposerServiceProvider
 * テンプレートとViewComposerの対応関係定義を行う
 * @package App\Providers
 * @author k.yamamoto@balocco.info
 */
class ViewComposerServiceProvider extends ServiceProvider
{

    public function boot()
    {
        //管理者ログイン情報
        View::composer(['admin.components.common-header'], AdminAuthComposer::class);
        View::composer(['admin.layouts.main.base'], AdminAuthComposer::class);
        View::composer(['admin.components.account.list'], AdminAuthComposer::class);
        View::composer(['admin.components.account.list_item'], AdminAuthComposer::class);
        View::composer(['admin.pages.mail.system'], AdminAuthComposer::class);
        View::composer(['admin.pages.system.holiday'], AdminAuthComposer::class);
        View::composer(['admin.components.system.tax_list_item'], AdminAuthComposer::class);
        View::composer(['admin.pages.system.tax'], AdminAuthComposer::class);
        View::composer(['admin.pages.customer.info'], AdminAuthComposer::class);
        View::composer(['admin.pages.customer.basic_search'], AdminAuthComposer::class);
        View::composer(['admin.pages.customer.detailed_search'], AdminAuthComposer::class);
        View::composer(['admin.pages.customer.marketing_search'], AdminAuthComposer::class);
        //システム情報
        View::composer(['admin.components.common-right-menu'], SystemInfoComposer::class);
        View::composer(['admin.components.common-right-menu'], SystemLogComposer::class);
    }

    public function register()
    {

    }


}