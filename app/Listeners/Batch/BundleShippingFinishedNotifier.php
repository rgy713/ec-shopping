<?php

namespace App\Listeners\Batch;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\Batch\BundleShippingFinished;
use App\Listeners\FailedWithQueue;
use App\Notifications\Batch\BundleShippingFinishedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BundleShippingFinishedNotifier implements ShouldQueue
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
     * @param  BundleShippingFinished $event
     * @return void
     */
    public function handle(BundleShippingFinished $event)
    {
        //システム管理者への通知を行う
        (new SystemAdministrator())->notify(new BundleShippingFinishedNotification());
        //運用管理者への通知を行う
        (new OperationAdministrator())->notify(new BundleShippingFinishedNotification());

    }
}
