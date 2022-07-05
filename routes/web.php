<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});
//
//Route::get('/coreui-test', function () {
//    return view('coreui-test');
//});
//Route::get('/admin-layout', function () {
//    return view('admin.layouts.main.base');
//});
//Route::get('/admin-sections', function () {
//    return view('admin.layouts.main.sections');
//});
//Route::get('/admin-contents', function () {
//    return view('admin.layouts.main.contents');
//});
//Route::get('/admin-sample', function () {
//    return view('admin.sample');
//});
//
//Auth::routes();


////管理画面、認証無しで表示できる画面
//Route::group(['prefix' => 'admin'], function() {
//    Route::get('login',     'Admin\Auth\LoginController@showLoginForm')->name('admin.login');
//    Route::post('login',    'Admin\Auth\LoginController@login');
//});

////管理画面、認証必要な画面
//Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function() {
//    Route::get('logout',   'Admin\Auth\LoginController@logout')->name('admin.logout');
//    Route::get('home',      'Admin\HomeController@index')->name('admin.home');
//
//    /**
//     * 2018-10-02時点での[フルリ管理システム.xlsx]を反映したメニュー構成に従ったルーティングの仮実装
//     */
//
//    /**
//     * customer 顧客管理
//     */
//    Route::get('customer/basic_search',   'Admin\Customer\BasicSearchController@index')->name('admin.customer.basic_search');
//    Route::get('customer/detailed_search',   'Admin\Customer\DetailedSearchController@index')->name('admin.customer.detailed_search');
//    Route::get('customer/create',   'Admin\Customer\CustomerController@create')->name('admin.customer.create');
//    Route::get('customer/edit/{id}',   'Admin\Customer\CustomerController@edit')->name('admin.customer.edit');
//    Route::get('customer/info/{id}',   'Admin\Customer\CustomerInfoController@index')->name('admin.customer.info');
//    Route::get('customer/marketing_search',   'Admin\Customer\MarketingSearchController@index')->name('admin.customer.marketing_search');
//
//    Route::get('customer/mail/history',   'Admin\Customer\MailHistoryController@history')->name('admin.customer.mail.history');
//
//    //受注管理
//    Route::get('order/create','Admin\Order\OrderController@create')->name('admin.order.create');
//    Route::get('order/edit/{id}','Admin\Order\OrderController@edit')->name('admin.order.edit');
//    Route::get('periodic/create','Admin\Periodic\PeriodicController@create')->name('admin.periodic.create');
//    Route::get('periodic/edit','Admin\Periodic\PeriodicController@edit')->name('admin.periodic.edit');
//    Route::get('order/shipping','Admin\Order\ShippingController@index')->name('admin.order.shipping');
//    Route::get('order/status','Admin\Order\StatusController@index')->name('admin.order.status');
//    Route::get('order/delivery_invoice','Admin\Order\DeliveryInvoiceController@index')->name('admin.order.delivery_invoice');
//    Route::get('order/utility','Admin\Order\UtilityController@index')->name('admin.order.utility');
//
//    Route::get('order/search','Admin\Order\OrderController@search')->name('admin.order.search');
//
//    //ポップアップ
//    Route::get('order/popup/create','Admin\Order\OrderController@popupCreate')->name('admin.order.popup.create');
//    Route::get('order/popup/edit/{id}','Admin\Order\OrderController@popupEdit')->name('admin.order.popup.edit');
//
//    //商品管理
//    Route::get('product/search','Admin\Product\ProductController@search')->name('admin.product.search');
//    Route::get('product/create','Admin\Product\ProductController@create')->name('admin.product.create');
//    Route::get('product/edit/{id}','Admin\Product\ProductController@edit')->name('admin.product.edit');
//
//    /**
//     * media 広告
//     */
//    Route::get('media/search','Admin\Media\MediaController@search')->name('admin.media.search');
//    Route::get('media/create','Admin\Media\MediaController@create')->name('admin.media.create');
//    Route::get('media/edit','Admin\Media\MediaController@edit')->name('admin.media.edit');
//    //ASP一覧
//    Route::get('media/asp','Admin\Media\AspController@index')->name('admin.media.asp');
//    //CSVインポート
//    Route::get('media/import','Admin\Media\ImportController@index')->name('admin.media.import');
//    //総合分析
//    Route::get('media/summary','Admin\Media\SummaryController@index')->name('admin.media.summary');
//    //タグ管理
//    Route::get('media/tag/list','Admin\Media\TagController@index')->name('admin.media.tag.list');
//    Route::get('media/tag/edit/{id}','Admin\Media\TagController@edit')->name('admin.media.tag.edit');
//    Route::get('media/tag/create','Admin\Media\TagController@create')->name('admin.media.tag.create');
//    Route::get('media/tag/page/{id}','Admin\Media\TagController@page')->name('admin.media.tag.page.info');
//
//
//    /**
//     * sales 売上
//     */
//    Route::get('sales/summary/accounting','Admin\Sales\SummaryController@accounting')->name('admin.sales.summary.accounting');
//    Route::get('sales/summary1','Admin\Sales\SummaryController@index')->name('admin.sales.summary1');
//    Route::get('sales/summary2','Admin\Sales\SummaryController@index')->name('admin.sales.summary2');
//    Route::get('sales/summary3','Admin\Sales\SummaryController@index')->name('admin.sales.summary3');
//
//    /**
//     * stepdm ステップDM
//     */
//    Route::get('stepdm/download','Admin\Stepdm\StepdmController@download')->name('admin.stepdm.download');
//    Route::get('stepdm/setting','Admin\Stepdm\StepdmController@setting')->name('admin.stepdm.setting');
//
//    /**
//     * mail メール
//     */
//    Route::get('mail/system','Admin\Mail\SystemController@index')->name('admin.mail.system');
//    Route::get('mail/template/list','Admin\Mail\TemplateController@index')->name('admin.mail.template');
//    Route::get('mail/template/edit/{id}','Admin\Mail\TemplateController@edit')->name('admin.mail.template.edit');
//    Route::get('mail/template/create/{type}','Admin\Mail\TemplateController@create')->name('admin.mail.template.create');
//    Route::get('mail/layout','Admin\Mail\LayoutController@index')->name('admin.mail.layout');
//    Route::get('mail/trigger/{id}','Admin\Mail\TriggerController@index')->name('admin.mail.trigger');
//
//    //テンプレートサンプル表示、
//    Route::get('mail/sample','Admin\Mail\LayoutController@sample')->name('admin.mail.sample');
//
//    Route::get('mail/layout/create','Admin\Mail\LayoutController@create')->name('admin.mail.layout.create');
//    Route::get('mail/layout/edit/{id}','Admin\Mail\LayoutController@edit')->name('admin.mail.layout.edit');
//
//    Route::get('mail/template/edit/{id}','Admin\Mail\TemplateController@edit')->name('admin.mail.template.edit');
//    Route::get('mail/send/order/{id}','Admin\Mail\SendController@order')->name('admin.mail.send.order');
//
//    //管理者設定
//    Route::get('account/list','Admin\Account\AccountController@index')->name('admin.account.list');
//    Route::get('account/create','Admin\Account\AccountController@create')->name('admin.account.create');
//    Route::get('account/edit/{id}','Admin\Account\AccountController@edit')->name('admin.account.edit');
//    Route::get('account/disable','Admin\Account\AccountController@disable')->name('admin.account.disable');
//    Route::get('account/enable','Admin\Account\AccountController@enable')->name('admin.account.enable');
//
//    //システム設定
//    Route::get('system/holiday','Admin\System\HolidayController@index')->name('admin.system.holiday');
//    Route::get('system/tax','Admin\System\TaxController@index')->name('admin.system.tax');
//
//});

