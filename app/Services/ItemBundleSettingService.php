<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 4/12/2019
 * Time: 11:37 PM
 */

namespace App\Services;

use App\Models\ItemBundleSetting;
use App\Models\Product;


class ItemBundleSettingService
{
    /**
     * @return mixed
     */
    public function getSettings()
    {
        $settings = ItemBundleSetting::select('t0.*', 't1.name as req_product_name', 't2.name as product_name')
            ->from(sprintf('%s as t0', ItemBundleSetting::make()->getTable()))
            ->leftJoin(sprintf('%s as t1', Product::make()->getTable()), 't0.req_product_id','t1.id')
            ->leftJoin(sprintf('%s as t2', Product::make()->getTable()), 't0.product_id','t2.id')
            ->orderBy('t0.req_product_id', 'ASC')
            ->orderBy('t0.req_periodic_count', 'ASC')
            ->orderBy('t0.product_id', 'ASC')
            ->get();

        return $settings;
    }
}