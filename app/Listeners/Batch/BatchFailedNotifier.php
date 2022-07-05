<?php

namespace App\Listeners\Batch;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\Batch\BatchFailed;
use App\Listeners\FailedWithQueue;
use App\Notifications\Batch\BatchFailedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BatchFailedNotifier implements ShouldQueue
{
    use FailedWithQueue;

    /**
     * BatchFailedNotifier constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BatchFailed  $event
     * @return void
     */
    public function handle(BatchFailed $event)
    {

        //システム管理者への通知を行う
        (new SystemAdministrator())->notify(new BatchFailedNotification($event->batchName, $event->errorMessage));
        //運用管理者への通知を行う
        (new OperationAdministrator())->notify(new BatchFailedNotification($event->batchName, $event->errorMessage));

    }
}
