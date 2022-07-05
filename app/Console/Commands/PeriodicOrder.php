<?php

namespace App\Console\Commands;

use App\Events\Batch\BatchFailed;
use App\Events\Batch\BatchStarted;
use App\Events\Batch\BatchWarning;
use App\Events\Batch\PeriodicOrderFinished;
use App\Services\Batch\PeriodicOrderService;
use Illuminate\Console\Command;

class PeriodicOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:periodic-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定期バッチ処理';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->log = new \Illuminate\Log\Writer(new \Monolog\Logger('PeriodicOrder log'));
        $this->log->useFiles(storage_path("logs/batch/PeriodicOrder.log"));
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
            $service = app(PeriodicOrderService::class);
            $exception_count = $service->run();
        } catch (\Exception $e) {
            $this->log->error("バッチ処理が失敗してやばいです。". $e->getMessage());
            event(new BatchFailed($this->description));
            return;
        }

        //バッチ処理中止
        if($exception_count == -1){
            $this->log->error("バッチ処理が中止されました。");
            event(new BatchFailed($this->description, "バッチ処理が中止されました"));
            return false;
        }

        if($exception_count > 0){
            $exception = "バッチ処理例外が{$exception_count}件発生しました。";
            $this->log->warning($exception);
            event(new BatchWarning($this->description, $exception));
        }

        $this->log->info("バッチ処理が成功しました");

        //バッチ処理完了
        event(new PeriodicOrderFinished());
    }
}
