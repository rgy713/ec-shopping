<?php

namespace App\Listeners\Batch;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\Batch\CreateAspMediaCodeFinished;
use App\Listeners\FailedWithQueue;
use App\Notifications\Batch\CreateAspMediaCodeFinishedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateAspMediaCodeFinishedNotifier implements ShouldQueue
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
     * @param  CreateAspMediaCodeFinished $event
     * @return void
     */
    public function handle(CreateAspMediaCodeFinished $event)
    {
        //システム管理者への通知を行う
        (new SystemAdministrator())->notify(new CreateAspMediaCodeFinishedNotification());
        //運用管理者への通知を行う
        (new OperationAdministrator())->notify(new CreateAspMediaCodeFinishedNotification());

    }
}
