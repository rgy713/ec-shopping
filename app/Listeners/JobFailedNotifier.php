<?php

namespace App\Listeners;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\JobFailed;
use App\Notifications\JobFailedNotification;

class JobFailedNotifier
{
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
     * @param  JobFailed $event
     * @return void
     */
    public function handle(JobFailed $event)
    {
        //暫定処理：例外内容をロギング
        $myLogger = new \Illuminate\Log\Writer(new \Monolog\Logger('Job Failed'));
        $myLogger->useFiles(storage_path("logs/job-failed.log"));
        $str = get_class($event->event) . PHP_EOL . $event->exception->getMessage();
        $myLogger->error($str);

        //通知
        (new SystemAdministrator())->notify(new JobFailedNotification($event));
        //通知
        (new OperationAdministrator())->notify(new JobFailedNotification($event));

    }
}
