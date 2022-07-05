<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/26/2019
 * Time: 7:18 PM
 */

namespace App\Listeners\Batch;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\Batch\CreateAspMediaCodeWarning;
use App\Listeners\FailedWithQueue;
use App\Notifications\Batch\CreateAspMediaCodeWarningNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateAspMediaCodeWarningNotifier
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
     * @param  CreateAspMediaCodeWarning $event
     * @return void
     */
    public function handle(CreateAspMediaCodeWarning $event)
    {
        //システム管理者への通知を行う
        (new SystemAdministrator())->notify(new CreateAspMediaCodeWarningNotification());
        //運用管理者への通知を行う
        (new OperationAdministrator())->notify(new CreateAspMediaCodeWarningNotification());

    }
}