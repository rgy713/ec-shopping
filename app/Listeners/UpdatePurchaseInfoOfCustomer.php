<?php

namespace App\Listeners;

use App\Events\Interfaces\GetCustomer;
use App\Events\ShippingScheduled;
use App\Services\OrderService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 顧客テーブルの購入関連情報を更新する
 * このイベントリスナを呼び出すイベントは、GetCustomerインターフェースを実装する。
 * Class UpdatePurchaseInfoOfCustomer
 * @package App\Listeners
 */
class UpdatePurchaseInfoOfCustomer implements ShouldQueue
{
    use FailedWithQueue;

    /**
     * @var OrderService
     */
    protected $orderService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService=$orderService;
    }

    /**
     * Handle the event.
     *
     * @param $event
     * @return void
     */
    public function handle(GetCustomer $event)
    {
        $this->orderService->updatePurchaseInfoOfCustomer($event->getCustomer()->id);
    }
}
