<?php

namespace App\Listeners\Batch;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\Batch\RegisterHolidaysFinished;
use App\Listeners\FailedWithQueue;
use App\Notifications\Batch\RegisterHolidaysFinishedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterHolidaysFinishedNotifier implements ShouldQueue
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
     * @param  RegisterHolidaysFinished  $event
     * @return void
     */
    public function handle(RegisterHolidaysFinished $event)
    {
        //システム管理者への通知を行う
        (new SystemAdministrator())->notify(new RegisterHolidaysFinishedNotification());
        //運用管理者への通知を行う
        (new OperationAdministrator())->notify(new RegisterHolidaysFinishedNotification());

    }
}
