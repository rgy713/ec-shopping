<?php

namespace App\Listeners\Batch;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\Batch\SendAutoMailFinished;
use App\Listeners\FailedWithQueue;
use App\Notifications\Batch\SendAutoMailFinishedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAutoMailFinishedNotifier implements ShouldQueue
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
     * @param  SendAutoMailFinished $event
     * @return void
     */
    public function handle(SendAutoMailFinished $event)
    {
        //システム管理者への通知を行う
        (new SystemAdministrator())->notify(new SendAutoMailFinishedNotification());
        //運用管理者への通知を行う
        (new OperationAdministrator())->notify(new SendAutoMailFinishedNotification());

    }
}
