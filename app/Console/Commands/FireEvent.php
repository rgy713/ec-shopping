<?php

namespace App\Console\Commands;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\AdditionalShippingAddressModifiedByCustomer;
use App\Events\Batch\AutoMergeFinished;
use App\Events\Batch\BatchStarted;
use App\Events\JobFailed;
use App\Notifications\Batch\AutoMergeFinishedNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

use App\Common\SystemMessage;
use App\Events\Batch\AggregatePortfolioFinished;
use App\Events\Batch\BatchFailed;
use App\Events\Batch\BundleShippingFinished;
use App\Events\Batch\CreateAspMediaCodeFinished;
use App\Events\Batch\DisableAdminFinished;
use App\Events\Batch\MarketingSummaryFinished;
use App\Events\Batch\PeriodicCountSummaryFinished;
use App\Events\Batch\PeriodicOrderFinished;
use App\Events\Batch\RegisterHolidaysFinished;
use App\Events\Batch\SendAutoMailFinished;
use App\Events\Batch\StepDirectMailFinished;
use App\Events\CustomerAddressModifiedByCustomer;
use App\Events\CustomerRegistered;
use App\Events\OrderConfirmed;
use App\Events\OrderShipped;
use App\Events\PeriodicInfoModifiedByCustomer;
use App\Events\PeriodicOrderShipped;
use App\Events\ShippingScheduled;
use App\Models\Customer;
use App\Models\Order;
use App\Models\PeriodicOrder;
use App\Services\AdminSettingService;
use App\Services\SystemLogService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class FireEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fire:event {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'イベント発火をテストする開発用コマンド。開発環境でのみ動作する。';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (config("app.env") !== 'develop') {
            $this->info('開発環境用コマンドのため、處理を中断しました。');
            return;
        }
        $name = $this->argument("name");
        //テスト発火用のモデル

        $order = Order::find(1);
        $periodicOrder = PeriodicOrder::find(1);

        //受注受付イベント
        if ($this->checkClass('OrderConfirmed')) {
            event(new OrderConfirmed($order, true));
            $this->info('OrderConfirmed fired.');
        }

        //発送イベント
        if ($this->checkClass('OrderShipped')) {
            event(new OrderShipped($order));
            $this->info('OrderShipped fired.');
        }

        //定期情報変更イベント
        if ($this->checkClass('PeriodicInfoModifiedByCustomer')) {
            $periodicOrder->shipping->address01='住所変更して通知フラグを立てる';
            event(new PeriodicInfoModifiedByCustomer($periodicOrder, $periodicOrder->getOriginal(),
                $periodicOrder->getAttributes(), $periodicOrder->shipping->getOriginal(), $periodicOrder->shipping->getAttributes()));
            $this->info('PeriodicInfoModifiedByCustomer fired.');
        }

        //定期受注発送イベント
        if ($this->checkClass('PeriodicOrderShipped')) {
            event(new PeriodicOrderShipped($order));
            $this->info('PeriodicOrderShipped fired.');
        }
        //発送手配
        if ($this->checkClass('ShippingScheduled')) {
            event(new ShippingScheduled($order));
            $this->info('ShippingScheduled fired.');
        }

        //顧客住所変更
        if ($this->checkClass('AdditionalShippingAddressModifiedByCustomer')) {
            $customer = Customer::find(1);
            $customer->address01='住所を変えてフラグを立てる';
            event(new AdditionalShippingAddressModifiedByCustomer($customer->getOriginal(),$customer->getAttributes()));
            $this->info('AdditionalShippingAddressModifiedByCustomer fired.');
        }

        //顧客登録時
        if ($this->checkClass('CustomerRegistered')) {
            $customer = Customer::find(1);
            event(new CustomerRegistered($customer));
            $this->info('CustomerRegistered fired.');
        }



        /**
         * バッチ系
         */

        //顧客住所変更
        if ($this->checkClass('AggregatePortfolioFinished')) {
            event(new AggregatePortfolioFinished());
            $this->info('AggregatePortfolioFinished fired.');
        }
        //
        if ($this->checkClass('AutoMergeFinished')) {
            event(new AutoMergeFinished());
            $this->info('AutoMergeFinished fired.');
        }
        //
        if ($this->checkClass('BundleShippingFinished')) {
            event(new BundleShippingFinished());
            $this->info('BundleShippingFinished fired.');
        }
        if ($this->checkClass('CreateAspMediaCodeFinished')) {
            event(new CreateAspMediaCodeFinished());
            $this->info('CreateAspMediaCodeFinished fired.');
        }
        if ($this->checkClass('DisableAdminFinished')) {
            event(new DisableAdminFinished());
            $this->info('DisableAdminFinished fired.');
        }
        if ($this->checkClass('MarketingSummaryFinished')) {
            event(new MarketingSummaryFinished());
            $this->info('MarketingSummaryFinished fired.');
        }
        if ($this->checkClass('PeriodicCountSummaryFinished')) {
            event(new PeriodicCountSummaryFinished());
            $this->info('PeriodicCountSummaryFinished fired.');
        }
        if ($this->checkClass('PeriodicOrderFinished')) {
            event(new PeriodicOrderFinished());
            $this->info('PeriodicOrderFinished fired.');
        }
        if ($this->checkClass('RegisterHolidaysFinished')) {
            event(new RegisterHolidaysFinished());
            $this->info('RegisterHolidaysFinished fired.');
        }
        if ($this->checkClass('SendAutoMailFinished')) {
            event(new SendAutoMailFinished());
            $this->info('SendAutoMailFinished fired.');
        }
        if ($this->checkClass('StepDirectMailFinished')) {
            event(new StepDirectMailFinished());
            $this->info('StepDirectMailFinished fired.');
        }

        if ($this->checkClass('BatchStarted')) {
            event(new BatchStarted('サンプルバッチ処理名'));
            $this->info('BatchStarted fired.');
        }
        if ($this->checkClass('BatchFailed')) {
            event(new BatchFailed('サンプルバッチ処理名'));
            $this->info('BatchFailed fired.');
        }


        /**
         * その他
         */
        if ($this->checkClass('JobFailed')) {
            $sampleEvent = new SendAutoMailFinished();
            $e=new \Exception();
            event(new JobFailed($sampleEvent,$e));
            $this->info('JobFailed fired.');
        }















    }

    protected function checkClass($name)
    {
        if ($name === $this->argument("name") || is_null($this->argument("name"))) {
            return true;
        }
        return false;

    }
}
