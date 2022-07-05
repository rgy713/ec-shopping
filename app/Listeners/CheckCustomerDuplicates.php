<?php

namespace App\Listeners;

use App\Events\CustomerRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 顧客の重複チェックを行う
 * Class CheckCustomerDuplicates
 * @package App\Listeners
 * @author k.yamamoto@balocco.info
 */
class CheckCustomerDuplicates
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * TODO:実装
     * @param  CustomerRegistered  $event
     * @return void
     */
    public function handle(CustomerRegistered $event)
    {
        //顧客の重複チェックを行う。
        
    }
}
