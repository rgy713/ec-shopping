<?php

namespace App\Models\Exports;

use App\Models\Traits\DefaultModelExport;
use Illuminate\Support\Facades\DB;

class ItemBundleSettingExport
{
    use DefaultModelExport;

    public function __construct()
    {
        $this->csv_type_id = 12;
        $this->query = DB::table('item_bundle_settings')
            ->join('products', 'item_bundle_settings.product_id', '=', 'products.id')
            ->join('products as req_products', 'item_bundle_settings.req_product_id', '=', 'req_products.id')
            ->orderby('req_products.id', 'products.id');
    }
}