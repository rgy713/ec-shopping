<?php

namespace App\Listeners\Batch;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\Batch\StepDirectMailFinished;
use App\Listeners\FailedWithQueue;
use App\Notifications\Batch\StepDirectMailFinishedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StepDirectMailFinishedNotifier implements ShouldQueue
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
     * @param  StepDirectMailFinished $event
     * @return void
     */
    public function handle(StepDirectMailFinished $event)
    {
        //システム管理者への通知を行う
        (new SystemAdministrator())->notify(new StepDirectMailFinishedNotification());
        //運用管理者への通知を行う
        (new OperationAdministrator())->notify(new StepDirectMailFinishedNotification());

    }
}
