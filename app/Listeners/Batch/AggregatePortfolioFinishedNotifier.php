<?php

namespace App\Listeners\Batch;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\Batch\AggregatePortfolioFinished;
use App\Events\JobFailed;
use App\Listeners\FailedWithQueue;
use App\Notifications\Batch\AggregatePortfolioFinishedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class AggregatePortfolioFinishedNotifier implements ShouldQueue
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
     * @param  AggregatePortfolioFinished $event
     * @return void
     */
    public function handle(AggregatePortfolioFinished $event)
    {

        //システム管理者への通知を行う
        (new SystemAdministrator())->notify(new AggregatePortfolioFinishedNotification($event));
        //運用管理者への通知を行う
        (new OperationAdministrator())->notify(new AggregatePortfolioFinishedNotification($event));

    }
}
