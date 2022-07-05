<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     * Event名：処理完了時は過去形、処理の途中は進行形、「Event」
     * Listener名：担当する処理の内容、「Notifier」 ※通知（Notification）系のクラスと区別しやすいように
     * @var array
     */
    protected $listen = [
        /**
         * システム全体に関与するイベント
         */
        //キューに投入したジョブが失敗した場合に発生するイベント
        'App\Events\JobFailed' => [
            'App\Listeners\JobFailedNotifier',
        ],

        /**
         * バッチ処理開始時
         */
        'App\Events\Batch\BatchStarted' => [
            'App\Listeners\Batch\BatchStartedNotifier',
        ],
        /**
         * バッチ処理の失敗時
         */
        'App\Events\Batch\BatchFailed' => [
            'App\Listeners\Batch\BatchFailedNotifier',
        ],
        /**
         * バッチ処理例外発生件数通知
         */
        'App\Events\Batch\BatchWarning' => [
            'App\Listeners\Batch\BatchWarningNotifier',
        ],
        /**
         * 各バッチ処理完了時イベント
         */
        'App\Events\Batch\AggregatePortfolioFinished' => [
            'App\Listeners\Batch\AggregatePortfolioFinishedNotifier',
        ],
        'App\Events\Batch\AutoMergeFinished' => [
            'App\Listeners\Batch\AutoMergeFinishedNotifier',
        ],
        'App\Events\Batch\BundleShippingFinished' => [
            'App\Listeners\Batch\BundleShippingFinishedNotifier',
        ],
        'App\Events\Batch\CheckOrderDataFinished' => [
            'App\Listeners\Batch\CheckOrderDataFinishedNotifier',
        ],
        'App\Events\Batch\CreateAspMediaCodeFinished' => [
            'App\Listeners\Batch\CreateAspMediaCodeFinishedNotifier',
        ],
        'App\Events\Batch\CreateAspMediaCodeWarning' => [
            'App\Listeners\Batch\CreateAspMediaCodeWarningNotifier',
        ],
        //管理アカウント無効化
        'App\Events\Batch\DisableAdminFinished' => [
            'App\Listeners\Batch\DisableAdminFinishedNotifier',
        ],
        //売上推移
        'App\Events\Batch\MarketingSummaryFinished' => [
            'App\Listeners\Batch\MarketingSummaryFinishedNotifier',
        ],
        //定期稼働者推移
        'App\Events\Batch\PeriodicCountSummaryFinished' => [
            'App\Listeners\Batch\PeriodicCountSummaryFinishedNotifier',
        ],
        'App\Events\Batch\PeriodicOrderFinished' => [
            'App\Listeners\Batch\PeriodicOrderFinishedNotifier',
        ],
        //休日・祝祭日
        'App\Events\Batch\RegisterHolidaysFinished' => [
            'App\Listeners\Batch\RegisterHolidaysFinishedNotifier',
        ],
        'App\Events\Batch\SendAutoMailFinished' => [
            'App\Listeners\Batch\SendAutoMailFinishedNotifier',
        ],
        'App\Events\Batch\StepDirectMailFinished' => [
            'App\Listeners\Batch\StepDirectMailFinishedNotifier',
        ],



        //顧客登録時
        'App\Events\CustomerRegistered' => [
            'App\Listeners\CustomerRegisteredNotifier',
            'App\Listeners\CheckCustomerDuplicates'//顧客重複のチェック
        ],

        //配送先住所変更時
        'App\Events\AdditionalShippingAddressModifiedByCustomer' => [
            'App\Listeners\CustomerAddressModifiedNotifier',
        ],
        //配送先住所追加時
        'App\Events\AdditionalShippingAddressRegisteredByCustomer' => [
            'App\Listeners\CustomerAddressModifiedNotifier',
        ],
        //顧客住所変更時
        'App\Events\CustomerAddressModifiedByCustomer' => [
            'App\Listeners\CustomerAddressModifiedNotifier',
        ],
        //定期情報の変更
        'App\Events\PeriodicInfoModifiedByCustomer' => [
            'App\Listeners\PeriodicInfoModifiedNotifier',
        ],

        //注文確定
        'App\Events\OrderConfirmed' => [
            'App\Listeners\OrderConfirmedNotifier',
            'App\Listeners\UpdatePurchaseInfoOfCustomer',//注文が1件増えるため、顧客の購入情報を更新
        ],
        //注文更新
        'App\Events\OrderUpdated' => [
            'App\Listeners\UpdatePurchaseInfoOfCustomer',//注文情報が更新されたので、顧客の購入情報を更新する
            'App\Listeners\UpdateOrderCountOfCustomerWithoutCancel',//キャンセル、返金される場合があるので、受注の購入回調整も必要
        ],
        //注文ステータスが更新された
        'App\Events\OrderStatusUpdated' => [
            //注文ステータスの更新を監視し、変更内容によって処理を振り分ける
            //仕様を明示するため、モデルのオブザーバではなくイベントとイベントリスナによる実装とする。
            'App\Listeners\OrderStatusUpdatedObserver',
        ],

        //売上として計上するステータスになった
        'App\Events\OrderAccounted' => [
            'App\Listeners\SaveOrderSalesTimestamp',
        ],

        //受注がキャンセルされた
        'App\Events\OrderCanceled' => [
            'App\Listeners\SaveOrderCanceledTimestamp',
        ],

        //配送手配完了
        'App\Events\ShippingScheduled' => [
            'App\Listeners\ShippingScheduledNotifier',
            'App\Listeners\SaveScheduleDate',//配送関連の日付保存
        ],
        //配送済み
        'App\Events\OrderShipped' => [
            'App\Listeners\OrderShippedNotifier',
            'App\Listeners\SaveShippedTimestamps',//配送した日時保存
        ],
        //定期配送済
        'App\Events\PeriodicOrderShipped' => [
            'App\Listeners\PeriodicOrderShippedNotifier',
        ],
        //パスワード変更依頼
        'App\Events\PasswordResettingRequested' => [
            'App\Listeners\PasswordResettingRequestedNotifier',
        ],
        //１組の顧客統合が完了：（バッチ処理による統合、管理画面での統合）
        'App\Events\PairOfCustomersMerged' => [
            'App\Listeners\UpdatePurchaseInfoOfCustomer',//統合により受注のひも付き状態が変わるため、顧客の購入情報の更新が必要。
            'App\Listeners\UpdateOrderCountOfCustomerWithoutCancel',//統合により受注のひも付き状態が変わるため、購入回調整が必要。
            'App\Listeners\CreateMergedShopMemo',//SHOPメモの作成
        ],
        //自動配信メールバッチ処理により、メール送信対象が1件見つかった
        'App\Events\Batch\AutoMailTargetOrderFound' => [
            'App\Listeners\Batch\SendAutoMailNotifier',
        ],
        //定期テーブルのレコード作成完了
        'App\Events\PeriodicOrderRegistered'=>[
            'App\Listeners\CheckPeriodicDuplicates'//定期重複のチェック
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
