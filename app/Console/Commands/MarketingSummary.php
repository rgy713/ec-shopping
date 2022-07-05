<?php

namespace App\Console\Commands;

use App\Events\Batch\BatchFailed;
use App\Events\Batch\BatchStarted;
use App\Events\Batch\MarketingSummaryFinished;
use App\Services\Batch\MarketingSummaryService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class MarketingSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:marketing-summary
                            {target_month? : 対象月}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '売上推移';
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

        $this->log = new \Illuminate\Log\Writer(new \Monolog\Logger('MarketingSummary log'));
        $this->log->useFiles(storage_path("logs/batch/MarketingSummary.log"));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $arguments = $this->arguments();
        $validator = Validator::make($arguments, [
            'target_month' => ['nullable', 'string', 'date_format:Ym'],
        ]);

        if ($validator->fails()) {
            $this->error(implode(" ", $validator->messages()->all()));
            return false;
        }

        //バッチ処理内容の実装
        $this->log->info("バッチ処理が開始されました。");

        try {
            $service = app(MarketingSummaryService::class);
            $exception_count = $service->run($arguments['target_month']);
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
        
        $this->log->info("バッチ処理が成功しました");
        //バッチ処理完了
        event(new MarketingSummaryFinished());

        return true;
    }
}