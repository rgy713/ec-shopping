<?php

namespace App\Models\Exports;

use App\Models\Traits\DefaultModelExport;
use Illuminate\Support\Facades\DB;

class AccountingExport
{
    use DefaultModelExport;

    public function __construct($accounting_type, $query)
    {
        $this->csv_type_id = $accounting_type == 1 ? 5 : 6;
        $this->query = $query;
        $this->query->join('shippings', 'shippings.order_id', 'orders.id')
            ->leftJoin('order_bundle_shippings', 'order_bundle_shippings.order_id', 'orders.id');
    }
}