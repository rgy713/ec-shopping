<?php

namespace App\Console\Commands;

use App\Events\Batch\BatchFailed;
use App\Events\Batch\BatchStarted;
use App\Events\Batch\BatchWarning;
use App\Events\Batch\BundleShippingFinished;
use App\Services\Batch\BundleShippingService;
use Illuminate\Console\Command;

class BundleShipping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:bundle-shipping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'まとめ配送バッチ処理';
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

        $this->log = new \Illuminate\Log\Writer(new \Monolog\Logger('BundleShipping log'));
        $this->log->useFiles(storage_path("logs/batch/BundleShipping.log"));
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
            $service = app(BundleShippingService::class);
            $exception_count = $service->run();
        } catch (\Exception $e) {
            $this->log->error("バッチ処理が失敗してやばいです。". $e->getMessage());
            event(new BatchFailed($this->description, $e->getMessage()));
            return false;
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
        event(new BundleShippingFinished());
    }
}
