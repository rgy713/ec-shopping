<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductPurchaseWarning;
use App\Models\ItemBundleSetting;
use App\Models\Exports\ProductPurchaseWarningExport;
use App\Models\Exports\ItemBundleSettingExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function createSave($params)
    {
        $product = new Product();
        $product->name = $params["product_name"];
        $product->code = $params["product_code"];
        $product->creator_id = request()->user()->id;
        $product->creator = $product->creator_id;
        $product->admin_visible = 1;
        $product->item_lineup_id = $params["item_lineup_id"];
        $product->volume = $params["product_volume"];
        $product->sales_target_id = $params["sales_target_id"];
        $product->sales_route_id = $params["sales_route_id"];

        $undelivered_summary_classification_id = $params["undelivered_summary_classification_id"];
        $product->undelivered_summary_classification_id = $undelivered_summary_classification_id;

        $marketing_summary_classification_id = $params["marketing_summary_classification_id"];
        if ($marketing_summary_classification_id == 2) {
            $product->is_lp = true;
            $product->is_periodic = false;
        }
        else if ($marketing_summary_classification_id == 3) {
            $product->is_lp = false;
            $product->is_periodic = true;
        }
        else if ($marketing_summary_classification_id == 4) {
            $product->is_lp = true;
            $product->is_periodic = true;
        }
        else {
            $product->is_lp = false;
            $product->is_periodic = false;
        }

        if ($undelivered_summary_classification_id == 1) {
            if ($marketing_summary_classification_id == 1) {
                $product->product_delivery_type_id = 1;
            }
            else if ($marketing_summary_classification_id == 2) {
                $product->product_delivery_type_id = 4;
            }
            else if ($marketing_summary_classification_id == 3) {
                $product->product_delivery_type_id = 3;
            }
            else if ($marketing_summary_classification_id == 4) {
                $product->product_delivery_type_id = 3;
            }
        }
        else if ($undelivered_summary_classification_id == 2) {
            $product->product_delivery_type_id = 5;
        }
        else if ($undelivered_summary_classification_id == 3) {

        }

        $product->user_visible = $params["user_visible"];
        $product->periodic_batch_discount_to_zero_flag = $params["periodic_batch_discount_to_zero_flag"];
        if (isset($params["periodic_first_nebiki"])) {
            $product->periodic_first_nebiki = $params["periodic_first_nebiki"];
        }
        $product->price = $params["product_price"];
        $product->catalog_price = $params["product_catalog_price"];
        if (isset($params["sale_limit_at_once"])) {
            $product->sale_limit_at_once = $params["sale_limit_at_once"];
        }
        if (isset($params["sale_limit_for_one_customer"])) {
            $product->sale_limit_for_one_customer = $params["sale_limit_for_one_customer"];
        }
        if (isset($params["fixed_payment_method_id"])) {
            $product->fixed_payment_method_id = $params["fixed_payment_method_id"];
        }
        if (isset($params["fixed_periodic_interval_id"])) {
            $product->fixed_periodic_interval_id = $params["fixed_periodic_interval_id"];
        }

        $product->save();

        $stock_keeping_unit_id = $params["stock_keeping_unit_id"];
        foreach($stock_keeping_unit_id as $sku) {
            $product_sku = new ProductSku();

            $product_sku->product_id = $product->id;
            $product_sku->stock_keeping_unit_id = $sku;
            $product_sku->save();
        }

        if (isset($params["product_purchase_warnings_type1"])) {
            $product_purchase_warnings_type1 = $params["product_purchase_warnings_type1"];
            foreach($product_purchase_warnings_type1 as $product_item) {
                $product_purchase_warning = new ProductPurchaseWarning();

                $product_purchase_warning->product_id = $product->id;
                $product_purchase_warning->product_id_to_warn = $product_item;
                $product_purchase_warning->purchase_warning_type_id = 1;
                $product_purchase_warning->save();
            }
        }

        if (isset($params["product_purchase_warnings_type2"])) {
            $product_purchase_warnings_type2 = $params["product_purchase_warnings_type2"];
            foreach($product_purchase_warnings_type2 as $product_item) {
                $product_purchase_warning = new ProductPurchaseWarning();

                $product_purchase_warning->product_id = $product->id;
                $product_purchase_warning->product_id_to_warn = $product_item;
                $product_purchase_warning->purchase_warning_type_id = 2;
                $product_purchase_warning->save();
            }
        }

        if (isset($params["product_logo"])) {
            $params["product_logo"]->storeAs(
                'public/products/image/', $product->id
            );
        }

        return $product;
    }

    public function editSave($params)
    {
        $id = $params["id"];

        $product = Product::find($id);

        $product->name = $params["product_name"];
        $product->code = $params["product_code"];
        $product->admin_visible = 1;
        $product->item_lineup_id = $params["item_lineup_id"];
        $product->volume = $params["product_volume"];
        $product->sales_target_id = $params["sales_target_id"];
        $product->sales_route_id = $params["sales_route_id"];

        $undelivered_summary_classification_id = $params["undelivered_summary_classification_id"];
        $product->undelivered_summary_classification_id = $undelivered_summary_classification_id;

        $marketing_summary_classification_id = $params["marketing_summary_classification_id"];
        if ($marketing_summary_classification_id == 2) {
            $product->is_lp = true;
            $product->is_periodic = false;
        }
        else if ($marketing_summary_classification_id == 3) {
            $product->is_lp = false;
            $product->is_periodic = true;
        }
        else if ($marketing_summary_classification_id == 4) {
            $product->is_lp = true;
            $product->is_periodic = true;
        }
        else {
            $product->is_lp = false;
            $product->is_periodic = false;
        }

        if ($undelivered_summary_classification_id == 1) {
            if ($marketing_summary_classification_id == 1) {
                $product->product_delivery_type_id = 1;
            }
            else if ($marketing_summary_classification_id == 2) {
                $product->product_delivery_type_id = 4;
            }
            else if ($marketing_summary_classification_id == 3) {
                $product->product_delivery_type_id = 3;
            }
            else if ($marketing_summary_classification_id == 4) {
                $product->product_delivery_type_id = 3;
            }
        }
        else if ($undelivered_summary_classification_id == 2) {
            $product->product_delivery_type_id = 5;
        }
        else if ($undelivered_summary_classification_id == 3) {

        }

        $product->user_visible = $params["user_visible"];
        $product->periodic_batch_discount_to_zero_flag = $params["periodic_batch_discount_to_zero_flag"];
        if (isset($params["periodic_first_nebiki"])) {
            $product->periodic_first_nebiki = $params["periodic_first_nebiki"];
        }
        $product->price = $params["product_price"];
        $product->catalog_price = $params["product_catalog_price"];
        if (isset($params["sale_limit_at_once"])) {
            $product->sale_limit_at_once = $params["sale_limit_at_once"];
        }
        if (isset($params["sale_limit_for_one_customer"])) {
            $product->sale_limit_for_one_customer = $params["sale_limit_for_one_customer"];
        }
        if (isset($params["fixed_payment_method_id"])) {
            $product->fixed_payment_method_id = $params["fixed_payment_method_id"];
        }
        if (isset($params["fixed_periodic_interval_id"])) {
            $product->fixed_periodic_interval_id = $params["fixed_periodic_interval_id"];
        }

        $product->save();


        $product->skus()->delete();

        $stock_keeping_unit_id = $params["stock_keeping_unit_id"];
        foreach($stock_keeping_unit_id as $sku) {
            $product_sku = new ProductSku();

            $product_sku->product_id = $product->id;
            $product_sku->stock_keeping_unit_id = $sku;
            $product_sku->save();
        }

        $product->purchaseWarnings()->delete();

        if (isset($params["product_purchase_warnings_type1"])) {
            $product_purchase_warnings_type1 = $params["product_purchase_warnings_type1"];
            foreach($product_purchase_warnings_type1 as $product_item) {
                $product_purchase_warning = new ProductPurchaseWarning();

                $product_purchase_warning->product_id = $product->id;
                $product_purchase_warning->product_id_to_warn = $product_item;
                $product_purchase_warning->purchase_warning_type_id = 1;
                $product_purchase_warning->save();
            }
        }

        if (isset($params["product_purchase_warnings_type2"])) {
            $product_purchase_warnings_type2 = $params["product_purchase_warnings_type2"];
            foreach($product_purchase_warnings_type2 as $product_item) {
                $product_purchase_warning = new ProductPurchaseWarning();

                $product_purchase_warning->product_id = $product->id;
                $product_purchase_warning->product_id_to_warn = $product_item;
                $product_purchase_warning->purchase_warning_type_id = 2;
                $product_purchase_warning->save();
            }
        }

        if (isset($params["product_logo"])) {
            $params["product_logo"]->storeAs(
                'public/products/image/', $product->id
            );
        }
    }

    public function search_result($products, $params)
    {
        $search_params=[];
        if (isset($params['product_id'])) {
            $id = $params['product_id'];
            $search_params['product_id'] = $id;
            $products->where('products.id', $id);
        }
        if (isset($params['product_code'])) {
            $code = $params['product_code'];
            $search_params['product_code'] = $code;
            $products->where('products.code', $code);
        }
        if (isset($params['product_name'])) {
            $name = $params['product_name'];
            $search_params['product_name'] = $name;
            $products->where('products.name', 'like', '%' . $name . '%');
        }
        if (isset($params['user_visible'])) {
            $user_visible = $params['user_visible'];
            $user_visible = array_map('intval', $user_visible);
            $search_params['user_visible'] = $user_visible;
            $products->whereIn('products.user_visible', $user_visible);
        }
        if (isset($params['item_lineup_id'])) {
            $item_lineup_id = $params['item_lineup_id'];
            $item_lineup_id = array_map('intval', $item_lineup_id);
            $search_params['item_lineup_id'] = $item_lineup_id;
            $products->whereIn('products.item_lineup_id', $item_lineup_id);
        }
        if (isset($params['sales_target_id'])) {
            $sales_target_id = $params['sales_target_id'];
            $sales_target_id = array_map('intval', $sales_target_id);
            $search_params['sales_target_id'] = $sales_target_id;
            $products->whereIn('products.sales_target_id', $sales_target_id);
        }
        if (isset($params['sales_route_id'])) {
            $sales_route_id = $params['sales_route_id'];
            $sales_route_id = array_map('intval', $sales_route_id);
            $search_params['sales_route_id'] = $sales_route_id;
            $products->whereIn('products.sales_route_id', $sales_route_id);
        }
        if (isset($params['undelivered_summary_classification_id'])) {
            $undelivered_summary_classification_id = $params['undelivered_summary_classification_id'];
            $undelivered_summary_classification_id = array_map('intval', $undelivered_summary_classification_id);
            $search_params['undelivered_summary_classification_id'] = $undelivered_summary_classification_id;
            $products->whereIn('products.undelivered_summary_classification_id', $undelivered_summary_classification_id);
        }
        if (isset($params['marketing_summary_classification_id'])) {
            $marketing_summary_classification_id = $params['marketing_summary_classification_id'];
            $marketing_summary_classification_id = array_map('intval', $marketing_summary_classification_id);
            $search_params['marketing_summary_classification_id'] = $marketing_summary_classification_id;

            $products->where(function($query) use ($marketing_summary_classification_id) {
                $index = 0;
                foreach($marketing_summary_classification_id as $marketing_id) {
                    if ($marketing_id == 1) {
                        if ($index == 0) {
                            $query->where([['products.is_lp', false], ['products.is_periodic', false]]);
                        }
                        else {
                            $query->orWhere([['products.is_lp', false], ['products.is_periodic', false]]);
                        }
                    }
                    else if ($marketing_id == 2) {
                        if ($index == 0) {
                            $query->where([['products.is_lp', true], ['products.is_periodic', false]]);
                        }
                        else {
                            $query->orWhere([['products.is_lp', true], ['products.is_periodic', false]]);
                        }
                    }
                    else if ($marketing_id == 3) {
                        if ($index == 0) {
                            $query->where([['products.is_lp', false], ['products.is_periodic', true]]);
                        }
                        else {
                            $query->orWhere([['products.is_lp', false], ['products.is_periodic', true]]);
                        }
                    }
                    else if ($marketing_id == 4) {
                        if ($index == 0) {
                            $query->where([['products.is_lp', true], ['products.is_periodic', true]]);
                        }
                        else {
                            $query->orWhere([['products.is_lp', true], ['products.is_periodic', true]]);
                        }
                    }

                    $index = $index + 1;
                }
            });
        }
        if (isset($params['product_volume'])) {
            $volume = $params['product_volume'];
            $volume = array_map('intval', $volume);
            $search_params['product_volume'] = $volume;
            $products->whereIn('products.volume', $volume);
        }

        if (isset($params['sort'])) {
            $sort = $params['sort'];
            $search_params['sort'] = $sort;
            if (!strcmp($sort, 'price_asc')) {
                $products->orderBy('products.price', 'ASC');
            }
            else if (!strcmp($sort, 'price_desc')) {
                $products->orderBy('products.price', 'DESC');
            }
            else if (!strcmp($sort, 'volume_asc')) {
                $products->orderBy('products.volume', 'ASC');
            }
            else if (!strcmp($sort, 'volume_desc')) {
                $products->orderBy('products.volume', 'DESC');
            }
        }

        if (isset($params['page'])) {
            $search_params['page'] = $params['page'];
        }

        $number_per_page = isset($params['number_per_page']) ? $params['number_per_page'] : 100;
        $search_params['number_per_page'] = $number_per_page;

        return $search_params;
    }

    public function delete($params)
    {
        $id = $params["id"];

        Product::destroy($id);
    }

    public function download($params)
    {
        $type = $params["type"];

        if ($type == 10) {
            $product_purchase_warnings = new ProductPurchaseWarningExport(1);
            return $product_purchase_warnings->download();
        }
        else if ($type == 11) {
            $product_purchase_warnings = new ProductPurchaseWarningExport(2);
            return $product_purchase_warnings->download();
        }
        else if ($type == 12) {
            $item_bundle_settings = new ItemBundleSettingExport();
            return $item_bundle_settings->download();
        }
    }
}