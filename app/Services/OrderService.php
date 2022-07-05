<?php


namespace App\Services;

use App\Events\OrderConfirmed;
use App\Events\OrderStatusUpdated;
use App\Events\OrderUpdated;
use App\Listeners\UpdatePurchaseInfoOfCustomer;
use App\Models\Customer;
use App\Models\DeliveryTime;
use App\Models\Order;
use App\Models\OrderBundleShipping;
use App\Models\Masters\OrderStatus;
use App\Models\Masters\OrderStateTransition;
use App\Models\Masters\PaymentMethod;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Shipping;
use App\Models\Delivery;
use App\Common\Api\ApiResponseData;
use App\Models\ShopMemo;
use App\Environments\Interfaces\Paygent as PaygentInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * 受注に関する処理
 * Class OrderService
 * @package App\Services
 * @author k.yamamoto@balocco.info
 */
class OrderService
{
    /**
     * 共通処理#110「キャンセル」であるステータスIDを配列で返す
     * @return array キャンセルである受注ステータスIDの配列
     * @author k.yamamoto@balocco.info
     */
    public function getCanceledStatuses(){
        return [
            16,
            17,
            18,
            19,
            20,
            21
        ];
    }

    /**
     * 共通処理#120「返金」であるステータスIDを配列で返す
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function getRefundedStatuses(){
        return [13];
    }

    /**
     * 共通処理#240 まとめ配送対象であるステータスIDを配列で返す
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function getBundleTargetStatuses(){
        return [
            1,
            8,
            9,
            11,
            15
        ];
    }

    /**
     * 発送済みステータス
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function getShippedStatuses(){
        return [5];
    }

    /**
     * 出荷手配完了のステータス
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function getShippingScheduledStatuses(){
        return [4];
    }

    /**
     * 売上計上日時を記録するステータス
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function getAccountedStatuses(){
        return [1,9];
    }

    /**
     * 共通処理#170~#172 注文受付処理
     * 受注データの作成を行う。作成した受注データは、#180 注文確定処理　を行われるまで有効化されない。
     * TODO:実装
     * @param Customer $customer 購入者
     * @param $createOrderParameters 受注データ作成のための1次元配列
     * @param $createOrderDetailParameters 受注詳細データ作成のための2次元配列
     * @param $createShippingsParameters 配送データ作成のための1次元配列
     * @param Carbon $whenOrdered 税率決定の基準日時
     * @return Order 作成した受注データ
     */
    public function acceptOrder(
        Customer $customer,
        $createOrderParameters,
        $createOrderDetailParameters,
        $createShippingsParameters,
        Carbon $whenOrdered
    ){
        $mode = 'create';
        $order =  new Order();
        if (isset($createOrderParameters['order_id'])) {
            $order = Order::find($createOrderParameters['order_id']);
            $mode = 'edit';
        }

        if (!is_null($customer)) {
            $order->customer_id = $customer->id;
            $order->email = $customer->email;
            $order->name01 = $customer->name01;
            $order->name02 = $customer->name02;
            $order->kana01 = $customer->kana01;
            $order->kana02 = $customer->kana02;
            $order->phone_number01 = $customer->phone_number01;
            if (isset($customer->phone_number02)) {
                $order->phone_number02 = $customer->phone_number02;
            }
            $order->zipcode = $customer->zipcode;
            $order->prefecture_id = $customer->prefecture_id;
            $order->address01 = $customer->address01;
            $order->address02 = $customer->address02;
            if (isset($customer->requests_for_delivery)) {
                $order->requests_for_delivery = $customer->requests_for_delivery;
            }
            $order->is_wholesale = $customer->wholesale_flag;
        }

        $orderStatusUpdated = $order->order_status_id != $createOrderParameters["order_status_id"];
        $order->order_status_id = $createOrderParameters["order_status_id"];

        $order->purchase_route_id = $createOrderParameters["purchase_route_id"];
        if (isset($createOrderParameters["display_purchase_warning_flag"])) {
            $order->display_purchase_warning_flag = $createOrderParameters["display_purchase_warning_flag"];
        }
        $order->purchase_warning_flag = false;
        if (isset($createOrderParameters["message_from_customer"])) {
            $order->message_from_customer = $createOrderParameters["message_from_customer"];
        }

        $tax = 0;
        if (isset($createOrderParameters["tax_rate"])) {
            $tax = $createOrderParameters["tax_rate"];
        }
        else {
            $tax = app(TaxService::class)->getRate($whenOrdered);
            $tax = is_null($tax) || !isset($tax) ? 0 : $tax;
        }

        $order->tax_rate = $tax;

        $payment_method_id = $createOrderParameters["payment_method_id"];
        $payment_method_name = PaymentMethod::find($payment_method_id)->name;
        $order->payment_method_id = $payment_method_id;
        $order->payment_method_name = $payment_method_name;
        $delivery_id = $createOrderParameters["order_delivery_id"];
        $delivery_name = Delivery::find($delivery_id)->name;
        $order->delivery_id = $delivery_id;
        $order->delivery_name = $delivery_name;

        $subtotal = 0;
        $catalog_price_subtotal = 0;
        $order_details = [];

        $is_sample = true;
        if (isset($createOrderDetailParameters['product_quantity'])) {
            $product_quantity = $createOrderDetailParameters['product_quantity'];
            $product_quantity = array_map('intval', $product_quantity);
            $products = Product::select('id','name','code','price','catalog_price','volume', 'undelivered_summary_classification_id')
                ->whereIn('id', array_keys($product_quantity))
                ->get()->keyBy('id')->toArray();
            foreach ($product_quantity as $product_id => $quantity) {
                if (isset($products[$product_id])) {
                    $order_detail = new OrderDetail();

                    $product = $products[$product_id];

                    $order_detail->product_id = $product_id;
                    $order_detail->product_name = $product['name'];
                    $order_detail->product_code = $product['code'];
                    $order_detail->price = $product['price'];
                    $order_detail->tax = round($order_detail->price * $tax);
                    $order_detail->catalog_price = $product['catalog_price'];
                    $order_detail->catalog_price_tax = round($order_detail->catalog_price * $tax);
                    $order_detail->quantity = $quantity;
                    $order_detail->volume = $product['volume'];

                    $subtotal = $subtotal + $quantity * $order_detail->price;
                    $catalog_price_subtotal = $catalog_price_subtotal + $quantity * $order_detail->catalog_price;

                    array_push($order_details, $order_detail);

                    if ($product['undelivered_summary_classification_id'] == 1) {
                        $is_sample = false;
                    }
                }
            }
        }
        $order->is_sample = $is_sample;

        if (false == isset($createOrderParameters['order_summary_disable']) || false == $createOrderParameters['order_summary_disable']) {
            $order->delivery_fee = $createOrderParameters["order_delivery_fee"];
            $order->delivery_fee_tax = round($tax * $order->delivery_fee);
            $order->payment_method_fee = $createOrderParameters["order_payment_method_fee"];
            $order->payment_method_fee_tax = round($tax * $order->payment_method_fee);
            $order->discount = $createOrderParameters["order_discount"];
            $order->subtotal = $subtotal;
            $order->subtotal_tax = round($tax * $order->subtotal);
            $order->catalog_price_subtotal = $catalog_price_subtotal;
            $order->catalog_price_subtotal_tax = round($tax * $order->catalog_price_subtotal);
            $order->discount_from_catalog_price = $catalog_price_subtotal - $subtotal;
            $order->discount_from_catalog_price_tax = $order->catalog_price_subtotal_tax - $order->subtotal_tax;
            $order->payment_total = $subtotal + $order->delivery_fee + $order->payment_method_fee - $order->discount;
            $order->payment_total_tax = round($tax * $order->payment_total);
            $order->total = $order->payment_total;
            $order->total_tax = $order->payment_total_tax;
        }

        if (isset($createOrderParameters["periodic_order_id"])) {
            $order->periodic_order_id = $createOrderParameters["periodic_order_id"];
        }
        if (isset($createOrderParameters["periodic_count"])) {
            $order->periodic_order_id = $createOrderParameters["periodic_count"];
        }

        $order->save();

        if (false == isset($createOrderParameters['order_summary_disable']) || false == $createOrderParameters['order_summary_disable']) {
            $order->details()->delete();
            foreach ($order_details as $order_detail) {
                $order->details()->save($order_detail);
            }
        }

        $shipping = new Shipping();
        if (isset($order->shipping)) {
            $shipping = $order->shipping;
        }

        $shipping->delivery_id = $delivery_id;
        $shipping->delivery_name = $delivery_name;
        if (isset($createShippingsParameters['shippings_requested_delivery_date'])) {
            $shipping->requested_delivery_date = $createShippingsParameters['shippings_requested_delivery_date'];
        }
        if (isset($createShippingsParameters['shippings_delivery_time_id'])) {
            $delivery_time_id = $createShippingsParameters['shippings_delivery_time_id'];
            $shipping->delivery_time_id = $delivery_time_id;
            $shipping->delivery_time_name = DeliveryTime::find($delivery_time_id)->name;
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
            $order->shipping()->save($shipping);
        }


        if (isset($createOrderParameters['shop_memo_note'])) {
            $shop_memo = new ShopMemo();

            $shop_memo->order_id = $order->id;
            $shop_memo->customer_id = $order->customer_id;
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
            $this->confirmOrder($order, false);
        }
        else {
            event(new OrderUpdated($order));

            if ($orderStatusUpdated) {
                event(new OrderStatusUpdated($order->order_status_id,$order));
            }
        }

        return $order;
    }

    /**
     * 共通処理#180 注文確定処理
     * @param Order $order
     * @param bool $notify 通知を行う場合true、通知しない場合false
     * @author k.yamamoto@balocco.info
     */
    public function confirmOrder(Order $order,$notify=true){
        $order->confirmed_timestamp=Carbon::now();
        $order->save();

        //注文確定処理の完了イベント
        event(new OrderConfirmed($order,$notify));

        event(new OrderStatusUpdated($order->order_status_id,$order));
    }

    /**
     * 共通処理#210
     * TODO:実装
     * 実行時点の受注テーブル状況を参照し、顧客テーブルの購入情報関連カラムを更新する。
     * @param $customerId
     */
    public function updatePurchaseInfoOfCustomer($customerId)
    {
        $customer = app(Customer::class)->find($customerId);

        $orders_model = $customer->orders()
            ->whereNotNull("orders.confirmed_timestamp")
            ->whereNotIn("orders.order_status_id", [13,16,17,18,19,20,21])
            ->where("orders.is_sample", false);

        $customer->first_buy_date = $orders_model->min("orders.created_at");
        $customer->last_buy_date = $orders_model->max("orders.created_at");
        $customer->buy_times = $orders_model->count();
        $customer->buy_total = $orders_model->sum(DB::raw("orders.payment_total + orders.payment_total_tax"));
        $customer->buy_volume = $orders_model
                                ->leftJoin("order_details", "orders.id", "=", "order_details.order_id")
                                ->sum(DB::raw("order_details.volume * order_details.quantity"));

        $customer->save();
    }

    /**
     * 共通処理#220
     * TODO:実装
     * 実行時点の受注テーブル状況を参照し、顧客に紐づいている受注の
     * 購入回（order_count_of_customer_without_cancel）を更新する。
     * @param $customerId 対象の顧客ID
     */
    public function updateOrderCountOfCustomerWithoutCancel($customerId)
    {
        $customer = app(Customer::class)->find($customerId);

        $orders = $customer->orders()
            ->whereNotNull("orders.confirmed_timestamp")
            ->whereNotIn("orders.order_status_id", [13,16,17,18,19,20,21])
            ->where("orders.is_sample", false)
            ->orderBy("created_at", "ASC")
            ->get();

        $rank = 0;
        foreach ($orders as $order){
            $rank += 1;
            $order->order_count_of_customer_without_cancel = $rank;
            $order->save();
        }
    }

    public function productSummary($params)
    {
        $sum_subtotal = 0;
        $selected_products = null;

        if (isset($params['product_quantity'])) {
            $product_quantity = $params['product_quantity'];
            $product_quantity = array_map('intval', $product_quantity);

            $product_ids = implode(',', array_keys($product_quantity));
            $product_quantity = implode(',', array_values($product_quantity));

            $products = DB::table('products')
                ->select(
                    'products.id',
                    'products.code',
                    'products.name',
                    'products.price',
                    DB::raw('products.price * products_quantity.quantity as subtotal'),
                    'products_quantity.quantity as quantity')
                ->join(DB::raw(sprintf('(select id, quantity from unnest(array[%s], array[%s]) as quantities(id, quantity)) as products_quantity', $product_ids, $product_quantity)), 'products_quantity.id', 'products.id');

            $selected_products = $products->get();
            $sum_subtotal = $products->sum(DB::raw('products.price * products_quantity.quantity'));
        }

        $tax = 0;
        if (isset($params["tax_rate"])) {
            $tax = $params["tax_rate"];
        }
        else {
            $tax = app(TaxService::class)->getRate();
            $tax = is_null($tax) || !isset($tax) ? 0 : $tax;
        }

        $delivery_fee = isset($params['order_delivery_fee']) ? $params['order_delivery_fee'] : 0;
        $payment_method_fee = isset($params['order_payment_method_fee']) ? $params['order_payment_method_fee'] : 0;
        $discount = isset($params['order_discount']) ? $params['order_discount'] : 0;

        $payment_total = $sum_subtotal + $delivery_fee + $payment_method_fee - $discount;
        $payment_total_tax = round($payment_total * $tax);

        $summary = [
            'products'=>$selected_products,
            'sum_subtotal'=>$sum_subtotal,
            'payment_total'=>$payment_total,
            'payment_total_tax'=>$payment_total_tax,
        ];

        return $summary;
    }

    public function update($request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'update_type' => ['required', Rule::in(['order_statuses', 'order', 'bundle'])],
        ]);

        $responseData = new ApiResponseData($request);
        $responseData->message = __("common.response.success");
        $responseData->status = "success";

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return $responseData;
        }

        $update_type = $params['update_type'];
        if (!strcmp($update_type, 'order_statuses')) {
            $responseData = $this->update_order_status($request);
        }
        else if (!strcmp($update_type, 'order')) {
            $responseData = $this->update_order($request);
        }
        else if (!strcmp($update_type, 'bundle')) {
            $responseData = $this->bundle_orders($request);
        }

        return $responseData;
    }

    public function update_order($request)
    {
        $responseData = new ApiResponseData($request);
        $responseData->message = __("admin.help_text.order.update_success");
        $responseData->status = "success";

        $params = $request->all();

        $validator = Validator::make($params, [
            'order_id' => ['required', 'exists:orders,id'],
            'status_id_list.*' => ['required', 'exists:order_statuses,id'],
        ]);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return $responseData;
        }

        $order_id = $params['order_id'];
        $status_id_list = $params['status_id_list'];
        $order_status_id = $status_id_list[$order_id];
        $status_chk_list = [$order_id];

        $response_message = $this->update_order_status_save($status_chk_list, $order_status_id);
        if ($response_message == null) {
            $delivery_slip_num = null;
            if (isset($params['delivery_slip_num'])) {
                $delivery_slip_num = $params['delivery_slip_num'];
                $delivery_slip_num = $delivery_slip_num[$order_id];
            }

            Shipping::where('order_id', $order_id)->update(['delivery_slip_num'=>$delivery_slip_num]);
        }
        else {
            $responseData->message = $response_message;
            $responseData->status = "error";
        }

        return $responseData;
    }

    public function update_order_status($request)
    {
        $responseData = new ApiResponseData($request);
        $responseData->message = __("admin.help_text.order.statuses_success");
        $responseData->status = "success";

        $params = $request->all();
        $validator = Validator::make($params, [
            'status_chk_list' => ['required', 'array', 'exists:orders,id'],
            'new_status_id' => ['required', 'exists:order_statuses,id'],
        ]);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return $responseData;
        }

        $status_chk_list = $params['status_chk_list'];
        $new_status_id = $params['new_status_id'];

        $response_message = $this->update_order_status_save($status_chk_list, $new_status_id);
        if ($response_message != null) {
            $responseData->message = $response_message;
            $responseData->status = "error";
        }

        return $responseData;
    }

    public function update_order_status_save($status_chk_list, $new_status_id)
    {
        $response_message = null;

        $order_status = OrderStatus::getKeyValueList();

        foreach ($status_chk_list as $order_id) {
            $order = Order::find($order_id);
            $old_status_id = $order->order_status_id;
            $not_allowed = OrderStateTransition::where([['status_id_from', $old_status_id], ['status_id_to', $new_status_id], ['permission', false]])->first();
            if ($not_allowed != null) {
                $old_status_name = isset($order_status[$old_status_id]) ? $order_status[$old_status_id] : '';
                $new_status_name = isset($order_status[$new_status_id]) ? $order_status[$new_status_id] : '';

                $response_message = sprintf('ステータス %s から ステータス %s　に変更することはできません。変更処理は中止されました。', $old_status_name, $new_status_name);

                return $response_message;
            }
        }

        foreach ($status_chk_list as $order_id) {
            $order = Order::find($order_id);
            $old_status_id = $order->order_status_id;
            if ($old_status_id != $new_status_id) {
                $order->order_status_id = $new_status_id;
                $order->save();

                event(new OrderStatusUpdated($order->order_status_id,$order));
            }
        }

        return $response_message;
    }

    public function bundle_orders($request)
    {
        $responseData = new ApiResponseData($request);
        $responseData->message = __("common.response.success");
        $responseData->status = "success";

        $params = $request->all();
        $validator = Validator::make($params, [
            'bundle_chk_list' => ['required', 'array', 'exists:orders,id'],
        ]);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return $responseData;
        }

        $bundle_chk_list = $params['bundle_chk_list'];
        $old_bundle_orders = OrderBundleShipping::select('order_id')->whereIn('parent_order_id', $bundle_chk_list)->get()->toArray();
        $bundle_chk_list = array_unique(array_merge($bundle_chk_list, $old_bundle_orders), SORT_NUMERIC);
        $bundle_parent_id = max($bundle_chk_list);

        OrderBundleShipping::whereIn('order_id', $bundle_chk_list)->orWhereIn('parent_order_id', $bundle_chk_list)->delete();

        $bundle_chk_list = array_diff($bundle_chk_list, [$bundle_parent_id]);

        $bundle_orders = [];
        $current_date = Carbon::now();
        foreach ($bundle_chk_list as $order_id) {
            $bundle_orders[] = ['parent_order_id'=>$bundle_parent_id, 'order_id'=>$order_id, 'created_at'=>$current_date, 'updated_at'=>$current_date];
        }

        OrderBundleShipping::insert($bundle_orders);

        return $responseData;
    }

    public function delete($params)
    {
        $delete_chk_list = $params["delete_chk_list"];

        foreach ($delete_chk_list as $id) {
            Order::destroy($id);
        }
    }

    public function search_result($orders, $params, $is_csv = false)
    {
        $is_shipping = isset($params['is_shipping']);

        $search_params=[];
        if (isset($params['order_id'])) {
            $id = $params['order_id'];
            $search_params['order_id'] = $id;
            $orders->where('orders.id', $id);
        }
        if (isset($params['order_customer_id'])) {
            $customer_id = $params['order_customer_id'];
            $search_params['order_customer_id'] = $customer_id;
            $orders->where('orders.customer_id', $customer_id);
        }
        if (isset($params['order_customer_name'])) {
            $name = $params['order_customer_name'];
            $search_params['order_customer_name'] = $name;
            $orders->where(function($query) use ($name) {
                $query->where('orders.name01', 'like', '%' . $name . '%');
                $query->orWhere('orders.name02', 'like', '%' . $name . '%');
            });
        }
        if (isset($params['payment_method_id'])) {
            $payment_method_id = $params['payment_method_id'];
            $payment_method_id = array_map('intval', $payment_method_id);
            $search_params['payment_method_id'] = $payment_method_id;
            $orders->whereIn('orders.payment_method_id', $payment_method_id);
        }
        if (isset($params['order_status_id'])) {
            $order_status_id = $params['order_status_id'];
            $order_status_id = array_map('intval', $order_status_id);
            $search_params['order_status_id'] = $order_status_id;
            $orders->whereIn('orders.order_status_id', $order_status_id);
        }
        if (isset($params['shippings_estimated_arrival_date_from'])) {
            $shippings_estimated_arrival_date_from= $params['shippings_estimated_arrival_date_from'];
            $search_params['shippings_estimated_arrival_date_from'] = $shippings_estimated_arrival_date_from;
            $orders->where('shippings.estimated_arrival_date', '>=', $shippings_estimated_arrival_date_from);
        }
        if (isset($params['shippings_estimated_arrival_date_to'])) {
            $shippings_estimated_arrival_date_to = $params['shippings_estimated_arrival_date_to'];
            $search_params['shippings_estimated_arrival_date_to'] = $shippings_estimated_arrival_date_to;
            $shippings_estimated_arrival_date_to = Carbon::createFromFormat('Y-m-d', $shippings_estimated_arrival_date_to)->endOfDay();
            $orders->where('shippings.estimated_arrival_date', '<=', $shippings_estimated_arrival_date_to);
        }
        if (isset($params['order_created_at_from'])) {
            $created_at_from = $params['order_created_at_from'];
            $search_params['order_created_at_from'] = $created_at_from;
            $orders->where('orders.created_at', '>=', $created_at_from);
        }
        if (isset($params['order_created_at_to'])) {
            $created_at_to = $params['order_created_at_to'];
            $search_params['order_created_at_to'] = $created_at_to;
            $created_at_to = Carbon::createFromFormat('Y-m-d', $created_at_to)->endOfDay();
            $orders->where('orders.created_at', '<=', $created_at_to);
        }
        if (isset($params['order_canceled_timestamp_from'])) {
            $canceled_timestamp_from = $params['order_canceled_timestamp_from'];
            $search_params['order_canceled_timestamp_from'] = $canceled_timestamp_from;
            $orders->where('orders.canceled_timestamp', '>=', $canceled_timestamp_from);
        }
        if (isset($params['order_canceled_timestamp_to'])) {
            $canceled_timestamp_to = $params['order_canceled_timestamp_to'];
            $search_params['order_canceled_timestamp_to'] = $canceled_timestamp_to;
            $canceled_timestamp_to = Carbon::createFromFormat('Y-m-d', $canceled_timestamp_to)->endOfDay();
            $orders->where('orders.canceled_timestamp', '<=', $canceled_timestamp_to);
        }
        if (isset($params['order_purchase_warning_flag'])) {
            $purchase_warning_flag = $params['order_purchase_warning_flag'];
            $search_params['order_purchase_warning_flag'] = $purchase_warning_flag;
            if ($purchase_warning_flag) {
                $orders->where('orders.purchase_warning_flag', true);
            }
        }
        if (isset($params['message_from_customer'])) {
            $message_from_customer = $params['message_from_customer'];
            $search_params['message_from_customer'] = $message_from_customer;
            if ($message_from_customer) {
                $orders->whereNotNull('orders.message_from_customer');
            }
        }
        if (isset($params['order_no_payment'])) {
            $no_payment = $params['order_no_payment'];
            $search_params['order_no_payment'] = $no_payment;
            if ($no_payment) {
                $orders->where('orders.payment_method_id', 5)->whereNull('orders.settlement_status_code');
            }
        }
        if (isset($params['order_bundle_shippings'])) {
            $order_bundle_shippings = $params['order_bundle_shippings'];
            $search_params['order_bundle_shippings'] = $order_bundle_shippings;
            if ($order_bundle_shippings) {
                $bundled_order_ids = DB::table('order_bundle_shippings')->select('parent_order_id as bundled_order_id')->whereNotNull('parent_order_id');
                if (!$is_shipping) {
                    $bundled_order_ids = DB::table('order_bundle_shippings')->select('order_id as bundled_order_id')->whereNotNull('order_id')->union($bundled_order_ids);
                }

                $orders->whereIn('orders.id', $bundled_order_ids);

                if ($is_shipping && !$is_csv) {
                    $bundled_order_ids = DB::table('order_bundle_shippings')->select('order_id as bundled_order_id')->whereNotNull('order_id');
                    $orders->whereNotIn('orders.id', $bundled_order_ids);
                }
            }
        }
        else {
            if ($is_shipping) {
                $bundled_order_ids = DB::table('order_bundle_shippings')->select('parent_order_id as bundled_order_id')->whereNotNull('parent_order_id');
                if (!$is_csv) {
                    $bundled_order_ids = DB::table('order_bundle_shippings')->select('order_id as bundled_order_id')->whereNotNull('order_id')->union($bundled_order_ids);
                }

                $orders->whereIn('orders.id', $bundled_order_ids);
            }
        }

        if (isset($params['sort'])) {
            $sort = $params['sort'];
            $search_params['sort'] = $sort;
            if (!strcmp($sort, 'id_asc')) {
                $orders->orderBy('orders.id', 'ASC');
            }
            else if (!strcmp($sort, 'id_desc')) {
                $orders->orderBy('orders.id', 'DESC');
            }
            else if (!strcmp($sort, 'payment_total_asc')) {
                $orders->orderBy('payment_total', 'ASC');
            }
            else if (!strcmp($sort, 'payment_total_desc')) {
                $orders->orderBy('payment_total', 'DESC');
            }
        }
        else {
            $orders->orderBy('orders.id', 'DESC');
            $search_params['sort'] = 'id_desc';
        }

        if (isset($params['page'])) {
            $search_params['page'] = $params['page'];
        }

        $number_per_page = isset($params['number_per_page']) ? $params['number_per_page'] : 100;
        $search_params['number_per_page'] = $number_per_page;

        return $search_params;
    }

    public function sendTelegramCreditCommitRevise($orderId, $settlementId, $paymentAmount)
    {
        /** @var PaygentInterface $paygentService */
        $paygentService = app(PaygentInterface ::class);

        $order = Order::find($orderId);

        $response = $paygentService->sendTelegramCreditCommitRevise($orderId, $settlementId, $paymentAmount);

        $data = [
            'result'=>$response->getResult(),
            'settlement_id'=>$order->settlement_id,
            'settlement_sub_status_code'=>$order->settlement_sub_status_code,
            'settlement_sub_response_code'=>$order->settlement_sub_response_code,
            'settlement_sub_response_detail'=>$order->settlement_sub_response_detail,
        ];

        return $data;
    }

    public function sendTelegramCreditCommitCancel($orderId, $settlementId)
    {
        /** @var PaygentInterface $paygentService */
        $paygentService = app(PaygentInterface ::class);

        $order = Order::find($orderId);

        $response = $paygentService->sendTelegramCreditCommitCancel($orderId, $settlementId);

        $data = [
            'result'=>$response->getResult(),
            'settlement_id'=>$order->settlement_id,
            'settlement_sub_status_code'=>$order->settlement_sub_status_code,
            'settlement_sub_response_code'=>$order->settlement_sub_response_code,
            'settlement_sub_response_detail'=>$order->settlement_sub_response_detail,
        ];

        return $data;
    }

    public function getSettlementCards($customer_id)
    {
        /** @var PaygentInterface $paygentService */
        $paygentService = app(PaygentInterface::class);

        $response = $paygentService->sendTelegramCreditStockGet($customer_id);
        $data = [];
        foreach ($response as $item) {
            $data[]=[
                "settlement_card_id"=>$item["customer_card_id"],
                "card_number"=>str_pad("", strlen($item["card_number"]) - 4, '*', STR_PAD_LEFT).substr($item["card_number"],-4),
                "card_valid_term"=>$item["card_valid_term"],
                "cardholder_name"=>$item["cardholder_name"],
            ];
        }
        return $data;
    }

    public function sendTelegramCreditStockDelete($customerId, $customerCardId)
    {
        /** @var PaygentInterface $paygentService */
        $paygentService = app(PaygentInterface ::class);

        $response = $paygentService->sendTelegramCreditStockDelete($customerId, $customerCardId);

        $data = [
            'result'=>$response->getResult(),
            'response_code'=>$response->getResponseCode(),
            'response_detail'=>$response->getResponseDetail(),
        ];

        return $data;
    }

    public function orderPaymentUpdate($params)
    {
        $order = app(Order::class)->find($params["order_id"]);

        $paygentService = app(PaygentInterface::class);

        $response = $paygentService->sendTelegramCreditAuth($order, $params);
        if ($response->getResult() == 0) {
            $data = $response->getData();

            $order->settlement_id = $data['payment_id'];
            $order->settlement_status_code = '020';
            $order->settlement_sub_status_code = '020';
            $order->settlement_masked_card_number = $data['masked_card_number'];
            if(isset($params["settlement_card_id"])) {
                $order->settlement_card_id = $params["settlement_card_id"];
            }
            $order->save();

            $response = $paygentService->sendTelegramCreditCommit($order->id, $order->settlement_id);
            if ($response->getResult() == 0) {
                $data = $response->getData();

                $order->settlement_id = $data['payment_id'];
                $order->settlement_sub_status_code = '022';

                $order->order_status_id = 1;
                $order->save();

                event(new OrderStatusUpdated($order->order_status_id,$order));
            }
        }

        return $response;
    }
}