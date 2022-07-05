<?php

namespace App\Common\KeyValueLists;

use App\Models\Product;

class ProductCodeList extends KeyValueList
{
    /** @var Product $model */
    protected $model;

    /**
     * PaymentList constructor.
     * @param Product $model
     */
    public function __construct($items = [])
    {
        $this->model = app(Product::class);
        parent::__construct($items);
    }

    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }

}