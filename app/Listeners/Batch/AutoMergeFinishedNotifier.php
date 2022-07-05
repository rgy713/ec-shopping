<?php

namespace App\Listeners\Batch;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\Batch\AutoMergeFinished;
use App\Listeners\FailedWithQueue;
use App\Notifications\Batch\AutoMergeFinishedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class AutoMergeFinishedNotifier implements ShouldQueue
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
     * @param  AutoMergeFinished $event
     * @return void
     */
    public function handle(AutoMergeFinished $event)
    {
        //システム管理者への通知を行う
        (new SystemAdministrator())->notify(new AutoMergeFinishedNotification());
        (new OperationAdministrator())->notify(new AutoMergeFinishedNotification());
    }
}
