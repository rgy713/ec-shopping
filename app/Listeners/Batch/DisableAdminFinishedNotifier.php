<?php

namespace App\Listeners\Batch;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\Batch\DisableAdminFinished;
use App\Listeners\FailedWithQueue;
use App\Notifications\Batch\DisableAdminFinishedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DisableAdminFinishedNotifier implements ShouldQueue
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
     * @param  DisableAdminFinished  $event
     * @return void
     */
    public function handle(DisableAdminFinished $event)
    {
        //システム管理者への通知を行う
        (new SystemAdministrator())->notify(new DisableAdminFinishedNotification());
        //運用管理者への通知を行う
        (new OperationAdministrator())->notify(new DisableAdminFinishedNotification());
    }
}
