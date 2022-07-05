<?php

namespace App\Console\Commands;

use App\Events\Batch\CheckOrderDataFinished;
use Illuminate\Console\Command;

class CheckOrderData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:check-order-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '受注データチェックバッチ処理';

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
        //バッチ処理内容の実装

        //バッチ処理完了
        event(new CheckOrderDataFinished());
    }
}
