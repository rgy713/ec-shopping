<?php

namespace App\Models\Exports;

use App\Models\Order;
use App\Models\Traits\DefaultModelExport;
use Illuminate\Support\Facades\DB;

class PeriodicOrderSearchResultExport
{
    use DefaultModelExport;

    public function __construct($query)
    {
        $this->csv_type_id = 3;
        $this->query = $query;
        $this->query->join('prefectures', 'periodic_orders.prefecture_id', 'prefectures.id')
            ->join('payment_methods', 'periodic_orders.payment_method_id', 'payment_methods.id');
    }
}