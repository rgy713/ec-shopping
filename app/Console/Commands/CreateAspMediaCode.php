<?php

namespace App\Console\Commands;

use App\Events\Batch\BatchFailed;
use App\Events\Batch\BatchStarted;
use App\Events\Batch\BatchWarning;
use App\Events\Batch\CreateAspMediaCodeFinished;
use App\Services\Batch\CreateAspMediaCodeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAspMediaCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:create-asp-media-code 
                            {year_month? : 対象月 年・月を示す6桁数字} 
                            {asp_media_id? : 対象ASP asp_media.id の値を受け付ける}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ASP用広告番号自動作成処理';
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

        $this->log = new \Illuminate\Log\Writer(new \Monolog\Logger('CreateAspMediaCode log'));
        $this->log->useFiles(storage_path("logs/batch/CreateAspMediaCode.log"));

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
            'year_month' => ['nullable', 'string', 'date_format:Ym'],
            'asp_media_id' => ['nullable', 'integer', 'min:1', 'max:32767', 'exists:asp_media,id'],
        ]);

        if ($validator->fails()) {
            $this->error(implode(" ", $validator->messages()->all()));
            return false;
        }

        //バッチ処理内容の実装
        $this->log->info("バッチ処理が開始されました。");
        try {
            $service = app(CreateAspMediaCodeService::class);
            $exception_count = $service->run($arguments['year_month'], $arguments['asp_media_id']);
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
        //バッチ処理完了
        event(new CreateAspMediaCodeFinished());
    }
}
