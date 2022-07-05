<?php


//管理画面、認証無しで表示できる画面
Route::get('login',     'Auth\LoginController@showLoginForm')->name('admin.login');
Route::post('login',    'Auth\LoginController@login');


//管理画面、ログイン認証が必要な画面
Route::group(['middleware' => ['auth:admin','admin_role']], function() {
    Route::get('logout',   'Auth\LoginController@logout')->name('admin.logout');
    Route::get('home',      'HomeController@index')->name('admin.home');

    /**
     * customer 顧客管理
     */
    Route::get('customer/basic_search',   'Customer\BasicSearchController@index')->name('admin.customer.basic_search');
    Route::post('customer/basic_search',   'Customer\BasicSearchController@search')->name('admin.customer.basic_search_result');
    Route::get('customer/detailed_search',   'Customer\DetailedSearchController@index')->name('admin.customer.detailed_search');
    Route::post('customer/detailed_search',   'Customer\DetailedSearchController@search')->name('admin.customer.detailed_search_result');
    Route::post('customer/detailed_search/download_csv',   'Customer\DetailedSearchController@download')->name('admin.customer.detail.download.csv');
    Route::get('customer/create',   'Customer\CustomerController@create')->name('admin.customer.create');
    Route::post('customer/delete',   'Customer\CustomerController@delete')->name('admin.customer.delete');
    Route::post('customer/create',   'Customer\CustomerController@createSave')->name('admin.customer.create_save');
    Route::get('customer/edit/{id}',   'Customer\CustomerController@edit')->name('admin.customer.edit');
    Route::get('customer/info/{id}',   'Customer\CustomerInfoController@index')->name('admin.customer.info');
    Route::post('customer/info/{id}',   'Customer\CustomerController@update')->name('admin.customer.update');
    Route::get('customer/info/back',   'Customer\CustomerInfoController@back')->name('admin.customer.info.back');
    Route::post('customer/periodic/interval_update', 'Customer\CustomerInfoController@periodicOrderIntervalUpdate')->name('admin.customer.periodic.interval.update');
    Route::post('customer/periodic/next_update', 'Customer\CustomerInfoController@periodicOrderNextUpdate')->name('admin.customer.periodic.next.update');
    Route::post('customer/periodic/fail_update', 'Customer\CustomerInfoController@periodicOrderFailUpdate')->name('admin.customer.periodic.fail.update');
    Route::post('customer/periodic/stop_update', 'Customer\CustomerInfoController@periodicOrderStopUpdate')->name('admin.customer.periodic.stop.update');
    Route::post('customer/periodic/payment_update', 'Customer\CustomerInfoController@periodicOrderPaymentUpdate')->name('admin.customer.periodic.payment.update');
    Route::post('customer/periodic/settlements', 'Customer\CustomerInfoController@getSettlementCards')->name('admin.customer.periodic.payment.settlements');
    Route::post('customer/attachment/upload', 'Customer\CustomerInfoController@attachmentUpload')->name('admin.customer.attachment.upload');
    Route::post('customer/attachment/delete', 'Customer\CustomerInfoController@attachmentDelete')->name('admin.customer.attachment.delete');
    Route::get('customer/marketing_search',   'Customer\MarketingSearchController@index')->name('admin.customer.marketing_search');
    Route::post('customer/marketing_search',   'Customer\MarketingSearchController@search')->name('admin.customer.marketing_search_result');
    Route::post('customer/marketing_search/download_csv',   'Customer\MarketingSearchController@downloadCsv')->name('admin.customer.marketing.download.csv');
    Route::get('customer/mail/history',   'Customer\MailHistoryController@history')->name('admin.customer.mail.history');

    //ポップアップ表示用の顧客登録
    Route::get('customer/popup/create',   'Customer\CustomerController@popupCreate')->name('admin.customer.popup.create');

    /**
     * 受注管理
     */
    Route::get('order/create','Order\OrderController@create')->name('admin.order.create');
    Route::get('order/popup/create','Order\OrderController@popupCreate')->name('admin.order.popup.create');
    Route::post('order/create','Order\OrderController@createInfo')->name('admin.order.create_info');
    Route::post('order/create/save','Order\OrderController@createSave')->name('admin.order.create_save');
    Route::get('order/edit/{id}','Order\OrderController@edit')->name('admin.order.edit');
    Route::get('order/popup/edit/{id}','Order\OrderController@popupEdit')->name('admin.order.popup.edit');
    Route::post('order/edit/{id?}','Order\OrderController@editInfo')->name('admin.order.edit_info');
    Route::post('order/edit/save','Order\OrderController@editSave')->name('admin.order.edit_save');
    Route::post('order/update','Order\OrderController@update')->name('admin.order.update');
    Route::post('order/delete','Order\OrderController@delete')->name('admin.order.delete');
    Route::post('order/product_summary','Order\OrderController@productSummary')->name('admin.order.product_summary');
    Route::post('order/sendTelegramCreditCommitRevise','Order\OrderController@sendTelegramCreditCommitRevise')->name('admin.order.sendTelegramCreditCommitRevise');
    Route::post('order/sendTelegramCreditCommitCancel','Order\OrderController@sendTelegramCreditCommitCancel')->name('admin.order.sendTelegramCreditCommitCancel');
    Route::post('order/sendTelegramCreditStockDelete','Order\OrderController@sendTelegramCreditStockDelete')->name('admin.order.sendTelegramCreditStockDelete');
    Route::post('order/settlements', 'Customer\CustomerInfoController@getSettlementCards')->name('admin.order.payment.settlements');
    Route::post('order/delivery_pdf','Order\ShippingController@deliveryPdf')->name('admin.order.delivery_pdf');
    Route::get('order/shipping','Order\OrderController@shipping')->name('admin.order.shipping');
    Route::post('order/shipping','Order\OrderController@searchResult')->name('admin.order.shipping.search_result');
    Route::post('order/shipping/delivery_pdf','Order\ShippingController@deliveryPdf')->name('admin.order.shipping.delivery_pdf');
    Route::post('order/shipping/download_csv','Order\OrderController@downloadCSV')->name('admin.order.shipping.download_csv');
    Route::get('order/status','Order\StatusController@index')->name('admin.order.status');

    Route::get('order/utility','Order\UtilityController@index')->name('admin.order.utility');
    Route::get('order/utility/reservation','Order\UtilityController@reservation')->name('admin.order.utility.reservation');
    Route::post('order/utility/reservation','Order\UtilityController@changeOrderStatus')->name('admin.order.utility.reservation');
    Route::get('order/utility/duplicate','Order\UtilityController@duplicate')->name('admin.order.utility.duplicate');
    Route::post('order/utility/duplicate','Order\UtilityController@mergeCustomer')->name('admin.order.utility.duplicate');
    Route::get('order/utility/cancel','Order\UtilityController@cancel')->name('admin.order.utility.cancel');
    Route::post('order/utility/cancel','Order\UtilityController@applyCancel')->name('admin.order.utility.cancel');

    Route::get('order/search','Order\OrderController@search')->name('admin.order.search');
    Route::post('order/search','Order\OrderController@searchResult')->name('admin.order.search_result');
    Route::post('order/download_csv','Order\OrderController@downloadCSV')->name('admin.order.download_csv');
    Route::get('periodic/create','Order\PeriodicController@create')->name('admin.periodic.create');
    Route::get('periodic/popup/create','Order\PeriodicController@popupCreate')->name('admin.periodic.popup.create');
    Route::post('periodic/create','Order\PeriodicController@createInfo')->name('admin.periodic.create_info');
    Route::post('periodic/create/save','Order\PeriodicController@createSave')->name('admin.periodic.create_save');
    Route::get('periodic/edit/{id}','Order\PeriodicController@edit')->name('admin.periodic.edit');
    Route::get('periodic/popup/edit/{id}','Order\PeriodicController@popupEdit')->name('admin.periodic.popup.edit');
    Route::post('periodic/edit/{id?}','Order\PeriodicController@editInfo')->name('admin.periodic.edit_info');
    Route::post('periodic/edit/save','Order\PeriodicController@editSave')->name('admin.periodic.edit_save');
    Route::get('periodic/search','Order\PeriodicController@search')->name('admin.periodic.search');
    Route::post('periodic/search','Order\PeriodicController@searchResult')->name('admin.periodic.search_result');
    Route::post('periodic/delete','Order\PeriodicController@delete')->name('admin.periodic.delete');
    Route::post('periodic/download_csv','Order\PeriodicController@downloadCSV')->name('admin.periodic.download_csv');

    /**
     * 商品管理
     */
    Route::get('product/search','Product\ProductController@search')->name('admin.product.search');
    Route::post('product/search','Product\ProductController@searchResult')->name('admin.product.search_result');
    Route::post('product/search_modal','Product\ProductController@searchResultModal')->name('admin.product.search_result_modal');
    Route::get('product/create','Product\ProductController@create')->name('admin.product.create');
    Route::get('product/create/{id?}','Product\ProductController@createCopy')->name('admin.product.create');
    Route::post('product/create/{id?}','Product\ProductController@createSave')->name('admin.product.create_save');
    Route::get('product/edit/{id}','Product\ProductController@edit')->name('admin.product.edit');
    Route::post('product/edit/{id}','Product\ProductController@editSave')->name('admin.product.edit_save');
    Route::post('product/delete','Product\ProductController@delete')->name('admin.product.delete');
    Route::get('product/download','Product\ProductController@download')->name('admin.product.download');
    Route::get('product/download_csv','Product\ProductController@downloadCSV')->name('admin.product.download_csv');

    /**
     * media 広告
     */
    Route::get('media/search','Media\MediaController@search')->name('admin.media.search');
    Route::post('media/search','Media\MediaController@searchResult')->name('admin.media.search_result');
    Route::get('media/create','Media\MediaController@create')->name('admin.media.create');
    Route::get('media/create/{id?}','Media\MediaController@createCopy')->name('admin.media.create');
    Route::post('media/create/{id?}','Media\MediaController@createSave')->name('admin.media.create_save');
    Route::get('media/edit/{id?}','Media\MediaController@edit')->name('admin.media.edit');
    Route::post('media/edit/{id?}','Media\MediaController@editSave')->name('admin.media.edit_save');
    Route::post('media/delete','Media\MediaController@delete')->name('admin.media.delete');
    Route::post('media/download_csv','Media\MediaController@downloadCSV')->name('admin.media.download_csv');
    //ASP一覧
    Route::get('media/asp','Media\AspController@index')->name('admin.media.asp');
    Route::post('media/asp','Media\AspController@createSave')->name('admin.media.asp_create');
    Route::post('media/asp_edit','Media\AspController@editSave')->name('admin.media.asp_edit');
    Route::post('media/asp_create_batch','Media\AspController@createBatch')->name('admin.media.asp_create_batch');
    //総合分析
    Route::get('media/summary','Media\SummaryController@index')->name('admin.media.summary');
    //タグ管理
    Route::get('media/tag/list','Media\TagController@index')->name('admin.media.tag.list');
    Route::get('media/tag/edit/{id}','Media\TagController@edit')->name('admin.media.tag.edit');
    Route::get('media/tag/create','Media\TagController@create')->name('admin.media.tag.create');
    Route::get('media/tag/page/{id}','Media\TagController@page')->name('admin.media.tag.page.info');


    /**
     * sales 売上
     */
    Route::get('sales/summary/accounting','Sales\SummaryController@accounting')->name('admin.sales.summary.accounting');
    Route::post('sales/summary/accounting','Sales\SummaryController@accountingSummary')->name('admin.sales.summary.accounting');
    Route::post('sales/summary/accounting/download_csv','Sales\SummaryController@accountingDownloadCsv')->name('admin.sales.summary.accounting.download_csv');
    Route::get('sales/summary/marketing','Sales\SummaryController@marketing')->name('admin.sales.summary.marketing');
    Route::post('sales/summary/marketing','Sales\SummaryController@marketingSummary')->name('admin.sales.summary.marketing');
    Route::post('sales/summary/marketing/download_csv','Sales\SummaryController@marketingDownloadCsv')->name('admin.sales.summary.marketing.download_csv');
    Route::get('sales/summary/periodic_count','Sales\SummaryController@periodicCount')->name('admin.sales.summary.periodic_count');
    Route::post('sales/summary/periodic_count','Sales\SummaryController@periodicCount')->name('admin.sales.summary.periodic_count');
    Route::post('sales/summary/periodic_count/download_csv','Sales\SummaryController@periodicCountDownloadCsv')->name('admin.sales.summary.periodic_count.download_csv');
    Route::get('sales/summary/wholesale','Sales\SummaryController@wholesale')->name('admin.sales.summary.wholesale');
    Route::post('sales/summary/wholesale','Sales\SummaryController@wholesaleSummary')->name('admin.sales.summary.wholesale');
    Route::post('sales/summary/wholesale/download_csv','Sales\SummaryController@wholesaleDownloadCsv')->name('admin.sales.summary.wholesale.download_csv');
    Route::get('sales/summary/age','Sales\SummaryController@age')->name('admin.sales.summary.age');
    Route::post('sales/summary/age','Sales\SummaryController@ageSummary')->name('admin.sales.summary.age');
    Route::post('sales/summary/age/download_csv','Sales\SummaryController@ageDownloadCsv')->name('admin.sales.summary.age.download_csv');
    Route::get('sales/summary/payment','Sales\SummaryController@payment')->name('admin.sales.summary.payment');
    Route::post('sales/summary/payment','Sales\SummaryController@paymentSummary')->name('admin.sales.summary.payment');
    Route::post('sales/summary/payment/download_csv','Sales\SummaryController@paymentDownloadCsv')->name('admin.sales.summary.payment.download_csv');

    /**
     * stepdm ステップDM
     */
    Route::get('stepdm/download','Stepdm\StepdmController@download')->name('admin.stepdm.download');
    Route::post('stepdm/download/csv','Stepdm\StepdmController@downloadCsv')->name('admin.stepdm.download.csv');
    Route::post('stepdm/download/pdf','Stepdm\StepdmController@downloadPdf')->name('admin.stepdm.download.pdf');
    Route::get('stepdm/setting','Stepdm\StepdmController@setting')->name('admin.stepdm.setting');

    /**
     * mail メール
     */
    Route::get('mail/system','Mail\SystemController@index')->name('admin.mail.system');
    Route::post('mail/system','Mail\SystemController@update')->name('admin.mail.system_update');
    Route::get('mail/template/list','Mail\TemplateController@index')->name('admin.mail.template');
    Route::get('mail/template/edit/{id}','Mail\TemplateController@edit')->name('admin.mail.template.edit');
    Route::post('mail/template/edit/{id}','Mail\TemplateController@update')->name('admin.mail.template.update');
    Route::post('mail/template/preview','Mail\TemplateController@preview')->name('admin.mail.template.preview');

    Route::get('mail/template/create/{type}','Mail\TemplateController@create')
        ->name('admin.mail.template.create')
        ->where('type', '[0-9]+');
    Route::post('mail/template/create/{type}','Mail\TemplateController@createSave')
        ->name('admin.mail.template.create_save')
        ->where('type', '[0-9]+');

    Route::get('mail/layout','Mail\LayoutController@index')->name('admin.mail.layout');
    Route::get('mail/trigger/{id}','Mail\TriggerController@index')->name('admin.mail.trigger');
    Route::post('mail/trigger/{id}','Mail\TriggerController@create')->name('admin.mail.trigger.create');

    //テンプレートサンプル表示、
    Route::get('mail/sample','Mail\LayoutController@sample')->name('admin.mail.sample');

    Route::get('mail/layout/create','Mail\LayoutController@create')->name('admin.mail.layout.create');
    Route::post('mail/layout/create','Mail\LayoutController@createSave')->name('admin.mail.layout.create_save');
    Route::get('mail/layout/edit/{id}','Mail\LayoutController@edit')->name('admin.mail.layout.edit');
    Route::post('mail/layout/edit/{id}','Mail\LayoutController@update')->name('admin.mail.layout.update');

    Route::get('mail/template/edit/{id}','Mail\TemplateController@edit')->name('admin.mail.template.edit');
    Route::get('mail/send/order/{id}','Mail\SendController@order')->name('admin.mail.send.order');
    Route::post('mail/send/order/{id}','Mail\SendController@sendMail')->name('admin.mail.send.order.send_mail');
    Route::post('mail/send/order/get_template','Mail\SendController@getMailTemplate')->name('admin.mail.send.order.get_template');


    /**
     * システム
     */

    //管理者設定
    Route::get('account/list','Account\AccountController@index')->name('admin.account.list');
    Route::get('account/create','Account\AccountController@create')->name('admin.account.create');
    Route::post('account/create','Account\AccountController@createSave')->name('admin.account.create_save');
    Route::get('account/edit/{id}','Account\AccountController@edit')->name('admin.account.edit');
    Route::post('account/edit/{id}','Account\AccountController@editSave')->name('admin.account.edit_save');
    Route::post('account/disable','Account\AccountController@disable')->name('admin.account.disable');
    Route::post('account/enable','Account\AccountController@enable')->name('admin.account.enable');

    //システム設定
    Route::get('system/holiday','System\HolidayController@index')->name('admin.system.holiday');
    Route::post('system/holiday','System\HolidayController@update')->name('admin.system.holiday.update');
    Route::post('system/holiday/create','System\HolidayController@create')->name('admin.system.holiday.create');
    Route::post('system/holiday/delete','System\HolidayController@delete')->name('admin.system.holiday.delete');
    Route::get('system/tax','System\TaxController@index')->name('admin.system.tax');
    Route::post('system/tax/delete','System\TaxController@delete')->name('admin.system.tax.delete');
    Route::post('system/tax/create','System\TaxController@create')->name('admin.system.tax.create');

    Route::get('system/csv_setting/{id}','System\CsvSettingController@index')->name('admin.system.csv_setting');
    Route::post('system/csv_setting/{id}','System\CsvSettingController@update')->name('admin.system.csv_setting');


    /**
     * CSVインポート
     */
    Route::get('order/delivery_slip','Order\DeliverySlipController@index')->name('admin.order.delivery_slip');
    Route::post('order/delivery_slip','Order\DeliverySlipController@import')->name('admin.order.delivery_slip');
    Route::get('import/call_center','Import\CallCenterController@index')->name('admin.import.call_center');
    Route::get('import/unreachable_email','Import\UnreachableEmailController@index')->name('admin.import.unreachable_email');
    //広告番号
    Route::get('media/import','Import\MediaController@index')->name('admin.media.import');
    Route::post('media/import','Import\MediaController@confirm')->name('admin.media.import.confirm');


});


