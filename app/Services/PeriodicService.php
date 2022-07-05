<?php


namespace App\Services;

use App\Events\PeriodicOrderRegistered;
use App\Models\Customer;
use App\Models\DeliveryTime;
use App\Models\ItemBundleSetting;
use App\Models\OrderDetail;
use App\Models\PaymentMethod;
use App\Models\PeriodicOrder;
use App\Models\PeriodicOrderDetail;
use App\Models\PeriodicShipping;
use App\Models\Product;
use App\Models\Shipping;
use App\Models\ShopMemo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;
use App\Environments\Interfaces\Paygent as PaygentInterface;

/**
 * 定期関連処理
 * Class PeriodicService
 * @package App\Services
 * @author k.yamamoto@balocco.info
 */
class PeriodicService
{
    /**
     * @var Delivery
     */
    protected $deliveryService;

    /**
     * @var TaxService
     */
    protected $taxService;

    /**
     * PeriodicService constructor.
     * @param Delivery $deliveryService
     * @param TaxService $taxService
     */
    public function __construct(Delivery $deliveryService,TaxService $taxService)
    {
        $this->deliveryService=$deliveryService;
        $this->taxService=$taxService;
    }

    public function acceptOrder(
        Customer $customer,
        $createOrderParameters,
        $createOrderDetailParameters,
        $createShippingsParameters,
        Carbon $whenOrdered
    ){
        $mode = 'create';
        $periodic =  new PeriodicOrder();
        if (isset($createOrderParameters['order_id'])) {
            $periodic = PeriodicOrder::find($createOrderParameters['order_id']);
            $mode = 'edit';
        }

        if (!is_null($customer)) {
            $periodic->customer_id = $customer->id;
            $periodic->email = $customer->email;
            $periodic->name01 = $customer->name01;
            $periodic->name02 = $customer->name02;
            $periodic->kana01 = $customer->kana01;
            $periodic->kana02 = $customer->kana02;
            $periodic->phone_number01 = $customer->phone_number01;
            if (isset($customer->phone_number02)) {
                $periodic->phone_number02 = $customer->phone_number02;
            }
            $periodic->zipcode = $customer->zipcode;
            $periodic->prefecture_id = $customer->prefecture_id;
            $periodic->address01 = $customer->address01;
            $periodic->address02 = $customer->address02;
            if (isset($customer->requests_for_delivery)) {
                $periodic->requests_for_delivery = $customer->requests_for_delivery;
            }
        }

        $delivery_id = $createOrderParameters["order_delivery_id"];
        $periodic->last_delivery_id = $delivery_id;
        $prefecture_id = $createShippingsParameters["delivery_prefecture"];

        $periodic->periodic_interval_type_id = $createOrderParameters["periodic_interval_type_id"];
        if ($periodic->periodic_interval_type_id == 1) {
            $periodic->interval_days = $createOrderParameters["interval_days"];
            $periodic->interval_month = null;
            $periodic->interval_date_of_month = null;
        }
        else {
            $periodic->interval_days = null;
            $periodic->interval_month = $createOrderParameters["interval_month"];
            $periodic->interval_date_of_month = $createOrderParameters["interval_date_of_month"];
        }
        if (isset($createOrderParameters["next_delivery_date"])) {
            $periodic->next_delivery_date = $createOrderParameters["next_delivery_date"];
        }
        else {
            $periodic->next_delivery_date = app(DeliveryService::class)->shortestDeliveryRequestDate($whenOrdered, $delivery_id, $prefecture_id);
        }
        $periodic->failed_flag = $createOrderParameters["periodic_failed_flag"];
        $periodic->stop_flag = $createOrderParameters["periodic_stop_flag"];
        if ($mode == 'create') {
            $periodic->periodic_count = 0;
            $periodic->duplication_warning_flag = false;
            $periodic->confirmed_timestamp = $whenOrdered;
            $periodic->last_delivery_date = null;
        }

        //$orderStatusUpdated = $order->order_status_id != $createOrderParameters["order_status_id"];
        //$order->order_status_id = $createOrderParameters["order_status_id"];
        $periodic->purchase_route_id = $createOrderParameters["purchase_route_id"];
        if (isset($createOrderParameters["message_from_customer"])) {
            $periodic->message_from_customer = $createOrderParameters["message_from_customer"];
        }
        if (isset($createOrderParameters["settlement_card_id"])) {
            $periodic->settlement_card_id = $createOrderParameters["settlement_card_id"];
        }
        if (isset($createOrderParameters["settlement_masked_card_number"])) {
            $periodic->settlement_masked_card_number = $createOrderParameters["settlement_masked_card_number"];
        }

        $tax = 0;
        if (isset($createOrderParameters["tax_rate"])) {
            $tax = $createOrderParameters["tax_rate"];
        }
        else {
            $tax = app(TaxService::class)->getRate($whenOrdered);
            $tax = is_null($tax) || !isset($tax) ? 0 : $tax;
        }

        $payment_method_id = $createOrderParameters["payment_method_id"];
        $payment_method_name = PaymentMethod::find($payment_method_id)->name;
        $periodic->payment_method_id = $payment_method_id;

        $subtotal = 0;
        $catalog_price_subtotal = 0;
        $periodic_details = [];

        if (isset($createOrderDetailParameters['selected_product_ids'])) {
            $product_quantity = $createOrderDetailParameters['product_quantity'];
            $product_quantity = array_map('intval', $product_quantity);
            $products = Product::select('id','name','code','price','catalog_price','volume', 'undelivered_summary_classification_id')
                ->whereIn('id', array_keys($product_quantity))
                ->get()->keyBy('id')->toArray();
            foreach ($product_quantity as $product_id => $quantity) {
                if (isset($products[$product_id])) {
                    $periodic_detail = new PeriodicOrderDetail();

                    $product = $products[$product_id];

                    $periodic_detail->product_id = $product_id;
                    $periodic_detail->product_name = $product['name'];
                    $periodic_detail->product_code = $product['code'];
                    $periodic_detail->quantity = $quantity;
                    $periodic_detail->volume = $product['volume'];

                    $subtotal = $subtotal + $quantity * $product['price'];
                    $catalog_price_subtotal = $catalog_price_subtotal + $quantity * $product['catalog_price'];

                    array_push($periodic_details, $periodic_detail);
                }
            }
        }

        if (false == isset($createOrderParameters['order_summary_disable']) || false == $createOrderParameters['order_summary_disable']) {
            $periodic->last_delivery_fee = $createOrderParameters["order_delivery_fee"];
            $periodic->last_delivery_fee_tax = round($tax * $periodic->last_delivery_fee);
            $periodic->last_payment_method_fee = $createOrderParameters["order_payment_method_fee"];
            $periodic->last_payment_method_fee_tax = round($tax * $periodic->last_payment_method_fee);
            $periodic->discount = $createOrderParameters["order_discount"];

            $periodic->last_subtotal = $subtotal;
            $periodic->last_subtotal_tax = round($tax * $periodic->last_subtotal);
            $periodic->last_payment_total = $subtotal + $periodic->last_delivery_fee + $periodic->last_payment_method_fee - $periodic->discount;
            $periodic->last_payment_total_tax = round($tax * $periodic->last_payment_total);
            $periodic->last_total = $periodic->last_payment_total;
            $periodic->last_total_tax = $periodic->last_payment_total_tax;
        }

        $periodic->save();

        if (false == isset($createOrderParameters['order_summary_disable']) || false == $createOrderParameters['order_summary_disable']) {
            $periodic->details()->delete();
            foreach ($periodic_details as $periodic_detail) {
                $periodic->details()->save($periodic_detail);
            }
        }

        $shipping = new PeriodicShipping();
        if (isset($periodic->shipping)) {
            $shipping = $periodic->shipping;
        }

        $shipping->last_delivery_id = $delivery_id;
        if (isset($createShippingsParameters['shippings_requested_delivery_date'])) {
            $shipping->requested_delivery_date = $createShippingsParameters['shippings_requested_delivery_date'];
        }
        if (isset($createShippingsParameters['shippings_delivery_time_id'])) {
            $delivery_time_id = $createShippingsParameters['shippings_delivery_time_id'];
            $shipping->requested_delivery_time = $delivery_time_id;
        }
        $shipping->name01 = $createShippingsParameters["delivery_name01"];
        $shipping->name02 = $createShippingsParameters["delivery_name02"];
        $shipping->kana01 = $createShippingsParameters["delivery_kana01"];
        $shipping->kana02 = $createShippingsParameters["delivery_kana02"];
        $shipping->phone_number01 = $createShippingsParameters["delivery_phone_number01"];
        if (isset($createShippingsParameters["delivery_phone_number02"])) {
            $shipping->phone_number02 = $createShippingsParameters["delivery_phone_number02"];
        }
        $shipping->zipcode = $createShippingsParameters["delivery_zipcode"];
        $shipping->prefecture_id = $createShippingsParameters["delivery_prefecture"];
        $shipping->address01 = $createShippingsParameters["delivery_address1"];
        $shipping->address02 = $createShippingsParameters["delivery_address2"];
        if (isset($createShippingsParameters["delivery_address3"])) {
            $shipping->requests_for_delivery = $createShippingsParameters["delivery_address3"];
        }

        if (isset($shipping->id)) {
            $shipping->save();
        }
        else {
            $periodic->shipping()->save($shipping);
        }


        if (isset($createOrderParameters['shop_memo_note'])) {
            $shop_memo = new ShopMemo();

            $shop_memo->periodic_order_id = $periodic->id;
            $shop_memo->customer_id = $periodic->customer_id;
            $shop_memo->created_by = Auth::user()->id;

            $shop_memo->note = $createOrderParameters['shop_memo_note'];
            if (isset($createOrderParameters['shop_memo_important'])) {
                $shop_memo->important = $createOrderParameters['shop_memo_important'];
            }
            if (isset($createOrderParameters['shop_memo_claim_flag'])) {
                $shop_memo->claim_flag = $createOrderParameters['shop_memo_claim_flag'];
            }

            $shop_memo->save();
        }

        if ($mode == 'create') {
            event(new PeriodicOrderRegistered());

            if ($periodic->next_delivery_date < $whenOrdered->addDays(7) ) {
                $new_order = $this->prepareParametersForAcceptOrder($periodic->id, $whenOrdered);
                if (!is_null($new_order)) {
                    $accepted_order = app(OrderService::class)->acceptOrder(
                        $customer,
                        $new_order['order'],
                        $new_order['orderDetail'],
                        $new_order['shipping'],
                        $whenOrdered
                    );

                    $this->updatePeriodicAfterOrderCreated1($accepted_order, $periodic);
                    $this->updatePeriodicAfterOrderCreated2($accepted_order, $periodic);
                    $this->updatePeriodicAfterOrderCreated3($accepted_order, $periodic);

                    if ($accepted_order->payment_method_id == 5 && $accepted_order->order_status_id == 22) {
                        $accepted_order->order_status_id = 8;

                        $response = app(PaygentInterface ::class)->execPeriodicOrder($accepted_order, $periodic);

                        if ($response->result == 0) {
                            $accepted_order->order_status_id = 9;
                        }
                        else {
                            $periodic->failed_flag = true;
                        }
                    }

                    $accepted_order->save();
                    $periodic->save();

                    app(OrderService::class)->confirmOrder($accepted_order, true);
                }
            }
        }
        else {

        }
    }

    /**
     * 共通処理#190~192 定期IDによる注文受付パラメータの準備（1）~（3）
     * TODO:実装
     * 引数の定期IDから、受注作成のための情報を取得する。
     * @param $periodicOrderId 対象の定期ID
     * @param Carbon $now 税率決定の基準日時
     */
    public function prepareParametersForAcceptOrder($periodicOrderId,Carbon $now)
    {
        $periodicOrder = PeriodicOrder::find($periodicOrderId);

        if (is_null($periodicOrder)) {
            return null;
        }

        $order = [];

        $order['periodic_order_id'] = $periodicOrderId;
        $order['payment_method_id'] = $periodicOrder->payment_method_id;
        $payment_method = PaymentMethod::find($periodicOrder->payment_method_id);
        if (is_null($payment_method)) {
            return null;
        }
        $tax = app(TaxService::class)->getRate($now);

        //$order['payment_method_name'] = $payment_method->name;
        //$order['payment_method_fee'] = $payment_method->fee;
        //$order['payment_method_fee_tax'] = round($tax * $payment_method->fee);

        $periodic_count = $periodicOrder->periodic_count + 1;
        $order['periodic_count'] = $periodic_count;

        $order['order_status_id'] = $payment_method->initial_periodic_batch_order_status_id;
        $order['purchase_route_id'] = $periodicOrder->purchase_route_id;

        $delivery_id = app(DeliveryService::class)->findDeliveryByProductDeliveryType($periodicOrder->last_delivery_id);
        $order['order_delivery_id'] = $delivery_id;

        if ($periodic_count == 1) {
            $order['message_from_customer'] = $periodicOrder->message_from_customer;
        }

        $order['order_delivery_fee'] = $periodicOrder->last_delivery_fee;
        $order['order_payment_method_fee'] = $periodicOrder->last_payment_method_fee;
        $order['order_discount'] = $periodicOrder->discount;

        $order['customer_id'] = $periodicOrder->customer_id;
        $order['email'] = $periodicOrder->email;
        $order['name01'] = $periodicOrder->name01;
        $order['name02'] = $periodicOrder->name02;
        $order['kana01'] = $periodicOrder->kana01;
        $order['kana02'] = $periodicOrder->kana02;
        $order['phone_number01'] = $periodicOrder->phone_number01;
        $order['phone_number02'] = $periodicOrder->phone_number02;
        $order['zipcode'] = $periodicOrder->zipcode;
        $order['prefecture_id'] = $periodicOrder->prefecture_id;
        $order['address01'] = $periodicOrder->address01;
        $order['address02'] = $periodicOrder->address02;
        $order['requests_for_delivery'] = $periodicOrder->requests_for_delivery;

        $periodicOrderDetail = PeriodicOrderDetail::select('product_id', 'quantity')
            ->where('periodic_order_id', $periodicOrderId)
            ->get()->keyBy('product_id')->toArray();
        $itemBundleSettings = ItemBundleSetting::select('product_id', 'quantity')
            ->where('enabled', true)
            ->where('req_periodic_count', $periodic_count)
            ->whereIn('req_product_id', array_keys($periodicOrderDetail))
            ->get()->keyBy('product_id')->toArray();
        $periodicOrderDetail = array_merge($periodicOrderDetail, $itemBundleSettings);

        $orderDetail = [];
        $orderDetail['product_quantity'] = $periodicOrderDetail;

        $periodShipping = $periodicOrder->shipping;

        $shipping = [];

        $shipping['delivery_email'] = $periodShipping->email;
        $shipping['delivery_name01'] = $periodShipping->name01;
        $shipping['delivery_name02'] = $periodShipping->name02;
        $shipping['delivery_kana01'] = $periodShipping->kana01;
        $shipping['delivery_kana02'] = $periodShipping->kana02;
        $shipping['delivery_phone_number01'] = $periodShipping->phone_number01;
        $shipping['delivery_phone_number02'] = $periodShipping->phone_number02;
        $shipping['delivery_zipcode'] = $periodShipping->zipcode;
        $shipping['delivery_prefecture'] = $periodShipping->prefecture_id;
        $shipping['delivery_address1'] = $periodShipping->address01;
        $shipping['delivery_address2'] = $periodShipping->address02;
        $shipping['delivery_address3'] = $periodShipping->requests_for_delivery;

        //$delivery = \App\Models\Delivery::find($delivery_id);
        //$shipping['delivery_id'] = $delivery_id;
        //$shipping['delivery_name'] = $delivery->name;

        if (isset($periodShipping->requested_delivery_time)) {
            $delivery_time = DeliveryTime::select('id')
                ->where('delivery_id', $delivery_id)
                ->where('time_range_from', '<=', $periodShipping->requested_delivery_time)
                ->where('time_range_to', '>=', $periodShipping->requested_delivery_time)
                ->first();
            if (!is_null($delivery_time)) {
                $shipping['shippings_delivery_time_id'] = $delivery_time->id;
            }
        }
        else {
            $shipping['shippings_delivery_time_id'] = null;
        }

        $shipping['shippings_requested_delivery_date'] = $periodicOrder->next_delivery_date;

        $shipping['delivery_slip_num'] = null;
        $shipping['scheduled_shipping_date'] = null;
        $shipping['estimated_arrival_date'] = null;
        $shipping['shipped_timestamp'] = null;

        return [
            'order' => $order,
            'orderDetail' => $orderDetail,
            'shipping' => $shipping,
        ];
    }

    //共通処理 定期受注作成後の定期更新処理(1)-(5)
    public function updatePeriodicAfterOrderCreated1($order, $periodicOrder)
    {
        $periodicOrder->periodic_count = $order->periodic_count;
        $periodicOrder->last_delivery_date = $order->shipping->requested_delivery_date;
        $periodicOrder->last_delivery_id = $order->delivery_id;
        $periodicOrder->last_delivery_fee = $order->delivery_fee;
        $periodicOrder->last_delivery_fee_tax = $order->delivery_fee_tax;
        $periodicOrder->last_payment_method_fee = $order->payment_method_fee;
        $periodicOrder->last_payment_method_fee_tax = $order->payment_method_fee_tax;
        $periodicOrder->last_subtotal = $order->subtotal;
        $periodicOrder->last_subtotal_tax = $order->subtotal_tax;
    }
    public function updatePeriodicAfterOrderCreated2($order, $periodicOrder)
    {
        $periodicOrder->next_delivery_date = app(PeriodicOrder::class)->createNextDeliveryDate();
    }
    public function updatePeriodicAfterOrderCreated3($order, $periodicOrder)
    {
        if ($order->periodic_count >= 1 && $periodicOrder->discount >= 1 ) {
            $discount_products_count = DB::table('order_details')
                ->select('order_details.i)')
                ->join('products', 'products.id', 'order_details.product_id')
                ->where('order_details.order_id', $order->id)
                ->where('products.periodic_batch_discount_to_zero_flag', true)
                ->count();

            if ($discount_products_count > 0) {
                $this->updatePeriodicAfterOrderCreated4($order, $periodicOrder, true);
                $periodicOrder->discount = 0;

                $this->updatePeriodicAfterOrderCreated5($order, $periodicOrder);
            }
        }

        $this->updatePeriodicAfterOrderCreated4($order, $periodicOrder, false);
    }
    public function updatePeriodicAfterOrderCreated4($order, $periodicOrder, $discountUpdated)
    {
        if ($discountUpdated) {
            $periodicOrder->last_payment_total = $order->payment_total + $periodicOrder->discount;
            $periodicOrder->last_payment_total_tax = round($periodicOrder->last_payment_total * $order->tax_rate);
        }
        else {
            $periodicOrder->last_payment_total = $order->payment_total;
            $periodicOrder->last_payment_total_tax = $order->payment_total_tax;
        }
    }
    public function updatePeriodicAfterOrderCreated5($order, $periodicOrder)
    {
        $shop_memo = new ShopMemo();

        $shop_memo->periodic_order_id = $periodicOrder->id;
        $shop_memo->order_id = null;
        $shop_memo->customer_id = $periodicOrder->customer_id;
        $shop_memo->created_by = 1;

        $shop_memo->note = sprintf('受注 %s作成後、初回値引きをリセット', $order->id);

        $shop_memo->save();
    }

    public function delete($params)
    {
        $id = $params["id"];

        PeriodicOrder::destroy($id);
    }

    public function search_result($orders, $params, $is_csv = false)
    {
        $search_params=[];
        if (isset($params['periodic_order_id'])) {
            $id = $params['periodic_order_id'];
            $search_params['periodic_order_id'] = $id;
            $orders->where('periodic_orders.id', $id);
        }
        if (isset($params['periodic_order_customer_id'])) {
            $customer_id = $params['periodic_order_customer_id'];
            $search_params['periodic_order_customer_id'] = $customer_id;
            $orders->where('periodic_orders.customer_id', $customer_id);
        }
        if (isset($params['periodic_order_customer_name'])) {
            $name = $params['periodic_order_customer_name'];
            $search_params['periodic_order_customer_name'] = $name;
            $orders->where(function($query) use ($name) {
                $query->where('periodic_orders.name01', 'like', '%' . $name . '%');
                $query->orWhere('periodic_orders.name02', 'like', '%' . $name . '%');
            });
        }
        if (isset($params['periodic_order_customer_kana'])) {
            $kana = $params['periodic_order_customer_kana'];
            $search_params['periodic_order_customer_kana'] = $kana;
            $orders->where(function($query) use ($kana) {
                $query->where('periodic_orders.kana01', 'like', '%' . $kana . '%');
                $query->orWhere('periodic_orders.kana02', 'like', '%' . $kana . '%');
            });
        }
        if (isset($params['payment_method_id'])) {
            $payment_method_id = $params['payment_method_id'];
            $payment_method_id = array_map('intval', $payment_method_id);
            $search_params['payment_method_id'] = $payment_method_id;
            $orders->whereIn('periodic_orders.payment_method_id', $payment_method_id);
        }
        if (isset($params['periodic_order_failed_flag'])) {
            $failed_flag = $params['periodic_order_failed_flag'];
            $search_params['periodic_order_failed_flag'] = $failed_flag;
            if ($failed_flag) {
                $orders->where('periodic_orders.failed_flag', true);
            }
        }
        if (isset($params['periodic_order_stop_flag'])) {
            $stop_flag = $params['periodic_order_stop_flag'];
            $search_params['periodic_order_stop_flag'] = $stop_flag;
            if ($stop_flag) {
                $orders->where('periodic_orders.stop_flag', true);
            }
        }
        if (isset($params['periodic_count'])) {
            $periodic_count = $params['periodic_count'];
            $search_params['periodic_count'] = $periodic_count;
            $orders->where('periodic_orders.periodic_count', $periodic_count);
        }
        if (isset($params['item_lineup_id'])) {
            $orders->leftJoin('periodic_order_details', 'periodic_order_details.periodic_order_id', 'periodic_orders.id')
                ->join('products', 'products.id', 'periodic_order_details.product_id');

            $item_lineup_id = $params['item_lineup_id'];
            $item_lineup_id = array_map('intval', $item_lineup_id);
            $search_params['item_lineup_id'] = $item_lineup_id;
            $orders->whereIn('products.item_lineup_id', $item_lineup_id);
        }
        if (isset($params['periodic_order_last_delivery_date_from'])) {
            $date_from= $params['periodic_order_last_delivery_date_from'];
            $search_params['periodic_order_last_delivery_date_from'] = $date_from;
            $orders->where('periodic_orders.last_delivery_date', '>=', $date_from);
        }
        if (isset($params['periodic_order_last_delivery_date_to'])) {
            $date_to= $params['periodic_order_last_delivery_date_to'];
            $search_params['periodic_order_last_delivery_date_to'] = $date_to;
            $date_to = Carbon::createFromFormat('Y-m-d', $date_to)->endOfDay();
            $orders->where('periodic_orders.last_delivery_date', '<=', $date_to);
        }
        if (isset($params['periodic_order_next_delivery_date_from'])) {
            $date_from= $params['periodic_order_next_delivery_date_from'];
            $search_params['periodic_order_next_delivery_date_from'] = $date_from;
            $orders->where('periodic_orders.next_delivery_date', '>=', $date_from);
        }
        if (isset($params['periodic_order_next_delivery_date_to'])) {
            $date_to= $params['periodic_order_next_delivery_date_to'];
            $search_params['periodic_order_next_delivery_date_to'] = $date_to;
            $date_to = Carbon::createFromFormat('Y-m-d', $date_to)->endOfDay();
            $orders->where('periodic_orders.next_delivery_date', '<=', $date_to);
        }

        if (isset($params['sort'])) {
            $sort = $params['sort'];
            $search_params['sort'] = $sort;
            if (!strcmp($sort, 'last_payment_total_asc')) {
                $orders->orderBy('periodic_orders.last_payment_total', 'ASC');
            }
            else if (!strcmp($sort, 'last_payment_total_desc')) {
                $orders->orderBy('periodic_orders.last_payment_total', 'DESC');
            }
            else if (!strcmp($sort, 'interval_days_asc')) {
                $orders->orderByRaw('CASE WHEN periodic_orders.periodic_interval_type_id = 2 THEN periodic_orders.interval_month * 30 ELSE periodic_orders.interval_days END ASC, periodic_orders.id ASC');
            }
            else if (!strcmp($sort, 'interval_days_desc')) {
                $orders->orderByRaw('CASE WHEN periodic_orders.periodic_interval_type_id = 2 THEN periodic_orders.interval_month * 30 ELSE periodic_orders.interval_days END DESC, periodic_orders.id DESC');
            }
        }

        if (isset($params['page'])) {
            $search_params['page'] = $params['page'];
        }

        $number_per_page = isset($params['number_per_page']) ? $params['number_per_page'] : 100;
        $search_params['number_per_page'] = $number_per_page;

        return $search_params;
    }

}