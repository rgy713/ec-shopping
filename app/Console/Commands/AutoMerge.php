<?php

namespace App\Console\Commands;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\Batch\AutoMergeFinished;
use App\Events\Batch\BatchFailed;
use App\Events\Batch\BatchStarted;
use App\Notifications\Batch\AutoMergeFinishedNotification;
use App\Services\Batch\AutoMergeService;
use Illuminate\Console\Command;

class AutoMerge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:auto-merge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自動統合バッチ処理';
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

        $this->log = new \Illuminate\Log\Writer(new \Monolog\Logger('AutoMerge log'));
        $this->log->useFiles(storage_path("logs/batch/AutoMerge.log"));
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
            $service = app(AutoMergeService::class);

            do{
                $result = $service->run();
            }while($result);

        } catch (\Exception $e) {
            $this->log->error("バッチ処理が失敗してやばいです。". $e->getMessage());
            event(new BatchFailed($this->description, $e->getMessage()));
            return;
        }

        $this->log->info("バッチ処理が成功しました");
        //バッチ処理完了
        event(new AutoMergeFinished());
    }
}
