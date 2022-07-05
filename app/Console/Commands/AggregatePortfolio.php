<?php

namespace App\Console\Commands;

use App\Common\SystemMessage;
use App\Events\Batch\AggregatePortfolioFinished;
use App\Events\Batch\BatchFailed;
use App\Events\Batch\BatchStarted;
use App\Events\Batch\BatchWarning;
use App\Services\Batch\AggregatePortfolioService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;

class AggregatePortfolio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:portfolio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ポートフォリオ集計バッチ';

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

        $this->log = new \Illuminate\Log\Writer(new \Monolog\Logger('AggregatePortfolio log'));
        $this->log->useFiles(storage_path("logs/batch/AggregatePortfolio.log"));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->log->info("バッチ処理が開始されました。");
        try {
            $service = app(AggregatePortfolioService::class);
            $exception_count = $service->run();
        } catch (\Exception $e) {
            $this->log->error("バッチ処理が失敗してやばいです。". $e->getMessage());
            event(new BatchFailed($this->description), $e->getMessage());
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
        event(new AggregatePortfolioFinished());

        return true;
    }
}
