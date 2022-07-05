<?php

namespace App\Listeners;

use App\Events\PairOfCustomersMerged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateMergedShopMemo implements ShouldQueue
{
    use FailedWithQueue;
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
     *
     * @param  PairOfCustomersMerged  $event
     * @return void
     */
    public function handle(PairOfCustomersMerged $event)
    {
        //
    }
}
