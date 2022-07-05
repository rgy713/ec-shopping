<?php

namespace App\Common\KeyValueLists;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Cache;

/**
 * Class PaymentList
 * @package App\Common\KeyValueLists
 * @author k.yamamoto@balocco.info
 */
class PaymentList extends KeyValueList
{

    /** @var PaymentMethod $model */
    protected $model;

    /**
     * PaymentList constructor.
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->model = app(PaymentMethod::class);
        parent::__construct($items);
    }

    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }

    public function getFeeValueList(){
        return Cache::rememberForever(self::class.'FeeValueList',function () {
            return $this->model->orderBy('rank')->get()->pluck('fee','id');
        });

    }

    public function getKeyValueListWithOrderStatusId($order_status_id){
        return $this->model->orderBy('rank')->where('initial_order_status_id', $order_status_id)->get()->toArray();

    }
}