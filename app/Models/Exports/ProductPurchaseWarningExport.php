<?php

namespace App\Models\Exports;

use App\Models\Traits\DefaultModelExport;
use Illuminate\Support\Facades\DB;

class ProductPurchaseWarningExport
{
    use DefaultModelExport;

    public function __construct($warning_type)
    {
        $this->csv_type_id = $warning_type == 2 ? 11 : 10;
        $this->query = DB::table('product_purchase_warnings')
            ->join('products', 'product_purchase_warnings.product_id', '=', 'products.id')
            ->join('products as products_to_warn', 'product_purchase_warnings.product_id_to_warn', '=', 'products_to_warn.id')
            ->where('product_purchase_warnings.purchase_warning_type_id', $warning_type)
            ->orderby('product_purchase_warnings.product_id', 'product_purchase_warnings.product_id_to_warn');
    }
}