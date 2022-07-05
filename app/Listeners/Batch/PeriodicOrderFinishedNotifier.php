<?php

namespace App\Listeners\Batch;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\Batch\PeriodicOrderFinished;
use App\Listeners\FailedWithQueue;
use App\Notifications\Batch\PeriodicOrderFinishedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PeriodicOrderFinishedNotifier implements ShouldQueue
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
     * @param  PeriodicOrderFinished $event
     * @return void
     */
    public function handle(PeriodicOrderFinished $event)
    {
        //システム管理者への通知を行う
        (new SystemAdministrator())->notify(new PeriodicOrderFinishedNotification());
        //運用管理者への通知を行う
        (new OperationAdministrator())->notify(new PeriodicOrderFinishedNotification());

    }
}
