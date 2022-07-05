<?php

namespace App\Listeners\Batch;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\Batch\MarketingSummaryFinished;
use App\Listeners\FailedWithQueue;
use App\Notifications\Batch\MarketingSummaryFinishedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MarketingSummaryFinishedNotifier implements ShouldQueue
{
    use FailedWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MarketingSummaryFinished  $event
     * @return void
     */
    public function handle(MarketingSummaryFinished $event)
    {
        //システム管理者への通知を行う
        (new SystemAdministrator())->notify(new MarketingSummaryFinishedNotification());
        //運用管理者への通知を行う
        (new OperationAdministrator())->notify(new MarketingSummaryFinishedNotification());

    }
}
