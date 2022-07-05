<?php

namespace App\Listeners\Batch;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\Batch\BatchStarted;
use App\Listeners\FailedWithQueue;
use App\Notifications\Batch\BatchStartedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BatchStartedNotifier implements ShouldQueue
{
    use FailedWithQueue;

    /**
     * BatchStartedNotifier constructor.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  BatchStarted  $event
     * @return void
     */
    public function handle(BatchStarted $event)
    {
        //システム管理者への通知を行う
        (new SystemAdministrator())->notify(new BatchStartedNotification($event->batchName));
        //運用管理者への通知を行う
        (new OperationAdministrator())->notify(new BatchStartedNotification($event->batchName));

    }
}
