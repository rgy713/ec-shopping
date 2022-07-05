<?php

namespace App\Listeners\Batch;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\Batch\CheckOrderDataFinished;
use App\Listeners\FailedWithQueue;
use App\Notifications\Batch\CheckOrderDataFinishedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckOrderDataFinishedNotifier implements ShouldQueue
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
     * @param  CheckOrderDataFinished $event
     * @return void
     */
    public function handle(CheckOrderDataFinished $event)
    {
        //システム管理者への通知を行う
        (new SystemAdministrator())->notify(new CheckOrderDataFinishedNotification());
        //運用管理者への通知を行う
        (new OperationAdministrator())->notify(new CheckOrderDataFinishedNotification());

    }
}
