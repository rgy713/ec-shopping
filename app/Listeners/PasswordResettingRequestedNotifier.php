<?php

namespace App\Listeners;

use App\Common\TmpNotifiable;
use App\Events\PasswordResettingRequested;
use App\Notifications\PasswordResettingRequestedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordResettingRequestedNotifier implements ShouldQueue
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
     * TODO:実装
     * Handle the event.
     * @param  PasswordResettingRequested  $event
     * @return void
     */
    public function handle(PasswordResettingRequested $event)
    {
        //通知
        (new TmpNotifiable($event->email))->notify(new PasswordResettingRequestedNotification());

    }
}
