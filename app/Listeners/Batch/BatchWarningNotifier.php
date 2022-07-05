<?php

namespace App\Listeners\Batch;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\Batch\BatchStarted;
use App\Events\Batch\BatchWarning;
use App\Listeners\FailedWithQueue;
use App\Notifications\Batch\BatchWarningNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BatchWarningNotifier implements ShouldQueue
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
    public function handle(BatchWarning $event)
    {
        //システム管理者への通知を行う
        (new SystemAdministrator())->notify(new BatchWarningNotification($event->batchName, $event->exception));
        //運用管理者への通知を行う
        (new OperationAdministrator())->notify(new BatchWarningNotification($event->batchName, $event->exception));

    }
}
