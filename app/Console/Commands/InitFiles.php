<?php

namespace App\Console\Commands;

use App\Common\OperationAdministrator;
use App\Common\SystemAdministrator;
use App\Events\Batch\AutoMergeFinished;
use App\Notifications\Batch\AutoMergeFinishedNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class InitFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初期ファイル配置:storage以下に初期状態のファイルを配置する';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //メールレイアウトのコピー
        $targetDir = Storage::path(config('fleuri.directory_path.mail_blade.layouts'));
        $success = \File::copyDirectory(resource_path('init_files/mail/layouts'), $targetDir);
        if($success){
            $this->info("[Succeed] : mail layouts to ".$targetDir);
        }else{
            $this->error("[Failed] : mail layouts to ".$targetDir);
        }

        //メールテンプレートのコピー
        $targetDir = Storage::path(config('fleuri.directory_path.mail_blade.templates'));
        $success = \File::copyDirectory(resource_path('init_files/mail/templates'), $targetDir);

        if($success){
            $this->info("[Succeed] : mail templates to ".$targetDir);
        }else{
            $this->error("[Failed] : mail templates to  ".$targetDir);
        }

    }
}
