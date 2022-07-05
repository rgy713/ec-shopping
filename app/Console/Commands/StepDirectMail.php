<?php

namespace App\Console\Commands;

use App\Events\Batch\BatchFailed;
use App\Events\Batch\BatchStarted;
use App\Events\Batch\BatchWarning;
use App\Events\Batch\StepDirectMailFinished;
use App\Services\Batch\StepDirectMailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class StepDirectMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:step-dm 
                            {target_date? : 対象日}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ステップDMデータ作成バッチ処理';
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

        $this->log = new \Illuminate\Log\Writer(new \Monolog\Logger('StepDirectMail log'));
        $this->log->useFiles(storage_path("logs/batch/StepDirectMail.log"));
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
            'target_date' => ['nullable', 'string', 'date_format:Ymd'],
        ]);

        if ($validator->fails()) {
            $this->error(implode(" ", $validator->messages()->all()));
            return false;
        }

        //バッチ処理内容の実装

        $this->log->info("バッチ処理が開始されました。");

        try {
            $service = app(StepDirectMailService::class);
            $exception_count = $service->run($arguments['target_date']);
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
        event(new StepDirectMailFinished());

        return true;
    }
}
