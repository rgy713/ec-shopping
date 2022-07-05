<?php

namespace App\Listeners;

use App\Events\Interfaces\GetCustomer;
use App\Events\PairOfCustomersMerged;
use App\Services\OrderService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 受注テーブルの購入回を更新する。
 * このイベントリスナを呼び出すイベントは、GetCustomerインターフェースを実装する。
 * Class UpdateOrderCountOfCustomerWithoutCancel
 * @package App\Listeners
 */
class UpdateOrderCountOfCustomerWithoutCancel implements ShouldQueue
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
     * @param GetCustomer $event
     */
    public function handle(GetCustomer $event)
    {
        $this->orderService->updateOrderCountOfCustomerWithoutCancel($event->getCustomer()->id);
    }
}
