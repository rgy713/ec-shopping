<?php

namespace App\Console\Commands;

use App\Events\Batch\BatchFailed;
use App\Events\Batch\BatchStarted;
use App\Events\Batch\SendAutoMailFinished;
use App\Services\Batch\SendAutoMailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class SendAutoMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:send-auto-mail
                            {target_date? : 対象日}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自動メール配信バッチ';

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

        $this->log = new \Illuminate\Log\Writer(new \Monolog\Logger('SendAutoMail log'));
        $this->log->useFiles(storage_path("logs/batch/SendAutoMail.log"));
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
            $service = app(SendAutoMailService::class);
            $service->run($arguments['target_date']);
        } catch (\Exception $e) {
            $this->log->error("バッチ処理が失敗してやばいです。". $e->getMessage());
            event(new BatchFailed($this->description, $e->getMessage()));
            return false;
        }

        $this->log->info("バッチ処理が成功しました");

        //バッチ処理完了
        event(new SendAutoMailFinished());
    }
}
