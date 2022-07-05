<?php

namespace App\Listeners;

use App\Events\JobFailed;


/**
 * キューに入れたジョブが失敗した場合の共通処理
 * ShouldQueueインターフェースを実装したイベントリスナクラスで利用する
 * Trait FailedWithQueue
 * @package App\Listeners
 */
trait FailedWithQueue
{
    /**
     * キュー試行回数
     * @var int
     */
    public $tries = 3;

    public function failed($event, \Exception $e)
    {
        event(new JobFailed($event, $e));
    }
}