<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/22/2019
 * Time: 12:18 AM
 */

namespace App\Console\Commands;

use App\Events\Batch\BatchFailed;
use App\Events\Batch\BatchStarted;
use App\Events\Batch\BatchWarning;
use App\Events\Batch\DisableAdminFinished;
use App\Services\Batch\DisableAdminService;
use Illuminate\Console\Command;

class DisableAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:disable-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '管理アカウント無効化';

    /**
     * @var \Illuminate\Log\Writer
     */
    protected $log;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->log = new \Illuminate\Log\Writer(new \Monolog\Logger('DisableAdmin log'));
        $this->log->useFiles(storage_path("logs/batch/DisableAdmin.log"));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //バッチ処理内容の実装

        $this->log->info("バッチ処理が開始されました。");

        try {
            $service = app(DisableAdminService::class);
            $exception_count = $service->run();
        } catch (\Exception $e) {
            $this->log->error("バッチ処理が失敗してやばいです。". $e->getMessage());
            event(new BatchFailed($this->description, $e->getMessage()));
            return;
        }

        if($exception_count > 0){
            $exception = "バッチ処理例外が{$exception_count}件発生しました。";
            $this->log->warning($exception);
            event(new BatchWarning($this->description, $exception));
        }

        $this->log->info("バッチ処理が成功しました");

        //バッチ処理完了
        event(new DisableAdminFinished());
    }
}
