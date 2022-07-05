<?php

namespace App\Http\Controllers\Admin\Order;

use App\Models\Customer;
use App\Models\OrderBundleShipping;
use App\Models\ShopMemo;
use App\Services\TaxService;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Exports\OrderSearchResultExport;
use App\Services\FlashToastrMessageService;
use App\Services\OrderService;
use App\Http\Controllers\Admin\BaseController;
use App\Common\Api\ApiResponseData;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends BaseController
{
    private $service;
    private $toastr;

    public function __construct()
    {
        $this->service = app(OrderService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    public function search(Request $request)
    {
        $account = Admin::find($request->user()->id);
        $editable = $account !== null && in_array($account->admin_role_id, [1, 2, 3, 4, 5, 7]);
        $deletable = $account !== null && in_array($account->admin_role_id, [1, 2]);

        $view_params = [
            "orders"=>[],
            "isShipping"=>false,
            "editable"=>$editable,
            "deletable"=>$deletable,
        ];

        $params = $request->all();
        if(isset($params['back'])) {
            $search_params = Cache::get('orders_search_params');

            $search_params['account'] = $account;
            return $this->getSearchResult($search_params);
        }

        return view("admin.pages.order.search",$view_params);
    }

    public function shipping(Request $request)
    {
        $account = Admin::find($request->user()->id);
        $editable = $account !== null && in_array($account->admin_role_id, [1, 2, 3, 4, 5, 7]);
        $deletable = $account !== null && in_array($account->admin_role_id, [1, 2]);

        $view_params = [
            "orders"=>null,
            "isShipping"=>true,
            "editable"=>$editable,
            "deletable"=>$deletable,
        ];

        $params = $request->all();
        if(isset($params['back'])) {
            $search_params = Cache::get('shippings_search_params');

            $search_params['is_shipping'] = true;
            $search_params['account'] = $account;
            return $this->getSearchResult($search_params);
        }

        return view("admin.pages.order.shipping",$view_params);
    }

    public function searchResult(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'order_id' => ['nullable', 'integer', 'min:1', 'max:2147483647'],
            'order_customer_id' => ['nullable', 'integer', 'min:1', 'max:2147483647'],
            'order_customer_name' => ['nullable', 'string', 'max:255'],
            'payment_method_id' => ['nullable', 'array', 'exists:payment_methods,id'],
            'order_status_id' => ['nullable', 'array', 'exists:order_statuses,id'],
            'shippings_estimated_arrival_date_from' => ['nullable', 'date'],
            'shippings_estimated_arrival_date_to' => 'nullable|date'. (isset($params['shippings_estimated_arrival_date_from']) ? '|after_or_equal:shippings_estimated_arrival_date_from' : ''),
            'order_created_at_from' => ['nullable', 'date'],
            'order_created_at_to' => 'nullable|date'. (isset($params['order_created_at_from']) ? '|after_or_equal:order_created_at_from' : ''),
            'order_canceled_timestamp_from' => ['nullable', 'date'],
            'order_canceled_timestamp_to' => 'nullable|date'. (isset($params['order_canceled_timestamp_from']) ? '|after_or_equal:order_canceled_timestamp_from' : ''),
            'order_purchase_warning_flag' => ['nullable', 'in:0,1'],
            'message_from_customer' => ['nullable', 'in:0,1'],
            'order_no_payment' => ['nullable', 'in:0,1'],
            'order_bundle_shippings' => ['nullable', 'in:0,1']
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $params['account'] = Admin::find($request->user()->id);

        return $this->getSearchResult($params);
    }

    public function getSearchResult($params)
    {
        $is_shipping = isset($params['is_shipping']);

        $orders = DB::table('orders')->whereNull('orders.deleted_at')
            ->select(
                'orders.id',
                'orders.name01',
                'orders.name02',
                'orders.payment_method_id',
                DB::Raw('orders.payment_total + orders.payment_total_tax as payment_total'),
                'orders.settlement_status_code',
                'orders.order_status_id',
                'orders.created_at',
                'order_statuses.color as color',
                'orders.display_purchase_warning_flag',
                'orders.purchase_warning_flag',
                'shippings.estimated_arrival_date as estimated_arrival_date',
                'shippings.delivery_slip_num as delivery_slip_num')
            ->leftJoin('shippings', 'orders.id', 'shippings.order_id')
            ->leftJoin('order_statuses', 'order_statuses.id', 'orders.order_status_id');

        if ($is_shipping) {
            $bundle_orders = DB::table('order_bundle_shippings')
                ->select(
                    'parent_order_id as parent_order_id',
                    DB::raw("string_agg(order_id::character varying, ',') as bundle_order_ids"))
                ->groupby('parent_order_id');

            $orders->leftJoin(DB::raw(sprintf('(%s) as bundle_orders', $bundle_orders->toSql())), 'bundle_orders.parent_order_id', 'orders.id');
            $orders->addSelect('bundle_orders.bundle_order_ids as bundle_order_ids');
        }

        $search_params = $this->service->search_result($orders, $params);

        $number_per_page = $search_params['number_per_page'];
        $orders = $orders->paginate($number_per_page, ['*'], 'page', isset($params['page']) ? $params['page'] : null);

        $account = $params['account'];
        $editable = $account !== null && in_array($account->admin_role_id, [1, 2, 3, 4, 5, 7]);
        $deletable = $account !== null && in_array($account->admin_role_id, [1, 2]);

        Cache::forever($is_shipping == false ? 'orders_search_params' : 'shippings_search_params', $search_params);

        $view_params = [
            "search_params"=>$search_params,
            "orders"=>$orders,
            "editable"=>$editable,
            "deletable"=>$deletable,
            "isShipping"=>$is_shipping,
        ];

        return view($is_shipping == false ? "admin.pages.order.search" : "admin.pages.order.shipping", $view_params);
    }

    public function downloadCSV(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'order_id' => ['nullable', 'integer', 'min:1', 'max:2147483647'],
            'order_customer_id' => ['nullable', 'integer', 'min:1', 'max:2147483647'],
            'order_customer_name' => ['nullable', 'string', 'max:255'],
            'payment_method_id' => ['nullable', 'array', 'exists:payment_methods,id'],
            'order_status_id' => ['nullable', 'array', 'exists:order_statuses,id'],
            'shippings_estimated_arrival_date_from' => ['nullable', 'date'],
            'shippings_estimated_arrival_date_to' => 'nullable|date'. (isset($params['shippings_estimated_arrival_date_from']) ? '|after_or_equal:shippings_estimated_arrival_date_from' : ''),
            'order_created_at_from' => ['nullable', 'date'],
            'order_created_at_to' => 'nullable|date'. (isset($params['order_created_at_from']) ? '|after_or_equal:order_created_at_from' : ''),
            'order_canceled_timestamp_from' => ['nullable', 'date'],
            'order_canceled_timestamp_to' => 'nullable|date'. (isset($params['order_canceled_timestamp_from']) ? '|after:order_canceled_timestamp_from' : ''),
            'order_purchase_warning_flag' => ['nullable', 'in:0,1'],
            'message_from_customer' => ['nullable', 'in:0,1'],
            'order_no_payment' => ['nullable', 'in:0,1'],
            'order_bundle_shippings' => ['nullable', 'in:0,1']
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $is_shipping = isset($params['is_shipping']);

        $orders = DB::table('orders')->whereNull('orders.deleted_at');

        $this->service->search_result($orders, $params);

        $orderSearchResultExport = app(OrderSearchResultExport::class, ['query'=>$orders, 'is_shipping'=>$is_shipping]);
        return $orderSearchResultExport->download();
    }

    public function create(Request $request)
    {
        return $this->createIndex($request, "admin.pages.order.create");
    }

    public function popupCreate(Request $request)
    {
        return $this->createIndex($request, "admin.pages.order.popup_create");
    }

    protected function createIndex(Request $request, $view_name)
    {
        $params = $request->all();
        if(isset($params['back'])){
            $order = Cache::get('order');

            $view_params=[
                'order'=>$order,
                'mode'=>'create',
                "isPeriodic"=>false,
            ];

            $customer_id = $order['customer_id'];
            $customer = Customer::find($customer_id);
            if (isset($customer)) {
                $customerShopMemos = $customer->shopMemos()->whereNull('deleted_by')
                    ->whereNull('order_id')->whereNull('periodic_order_id')
                    ->orderBy('important', 'desc')
                    ->orderBy('claim_flag', 'desc')
                    ->orderBy('created_at', 'desc')->get();

                $view_params['customerShopMemos'] = $customerShopMemos;
            }

            return view($view_name, $view_params);
        }
        else {
            $view_params=[
                'mode'=>'create',
                "isPeriodic"=>false,
            ];

            if (isset($params['customer_id'])) {
                $customer_id = $params['customer_id'];
                $customer = Customer::find($customer_id);
                if (isset($customer)) {
                    $order = [];

                    $order['old_customer_id'] = $customer->id;
                    $order['customer_id'] = $customer->id;
                    $order['customer_email'] = $customer->email;
                    $order['customer_advertising_media_code'] = $customer->advertising_media_code;
                    $order['customer_name01'] = $customer->name01;
                    $order['customer_name02'] = $customer->name02;
                    $order['customer_kana01'] = $customer->kana01;
                    $order['customer_kana02'] = $customer->kana02;
                    $order['customer_phone_number01'] = $customer->phone_number01;
                    $order['customer_phone_number02'] = $customer->phone_number02;
                    $order['customer_zipcode'] = $customer->zipcode;
                    $order['customer_prefecture'] = $customer->prefecture_id;
                    $order['customer_address1'] = $customer->address01;
                    $order['customer_address2'] = $customer->address02;
                    $order['customer_address3'] = $customer->requests_for_delivery;

                    $view_params['order'] = $order;

                    $customerShopMemos = $customer->shopMemos()->whereNull('deleted_by')
                        ->whereNull('order_id')->whereNull('periodic_order_id')
                        ->orderBy('important', 'desc')
                        ->orderBy('claim_flag', 'desc')
                        ->orderBy('created_at', 'desc')->get();

                    $view_params['customerShopMemos'] = $customerShopMemos;
                }
            }

            return view($view_name, $view_params);
        }
    }

    public function createInfo(Request $request)
    {
        $params = $request->all();

        $isPopup = isset($params['is_popup']);

        $messages = [
            'customer_email.regex' => __('validation.email'),
            'customer_kana01.regex'=>__('validation.hint_text.customer_kana'),
            'customer_kana02.regex'=>__('validation.hint_text.customer_kana'),
            'customer_phone_number01.regex'=>__('validation.hint_text.customer_phone_number'),
            'customer_phone_number02.regex'=>__('validation.hint_text.customer_phone_number'),
            'customer_zipcode.regex'=>__('validation.hint_text.customer_zipcode'),
            'delivery_kana01.regex'=>__('validation.hint_text.customer_kana'),
            'delivery_kana02.regex'=>__('validation.hint_text.customer_kana'),
            'delivery_phone_number01.regex'=>__('validation.hint_text.customer_phone_number'),
            'delivery_phone_number02.regex'=>__('validation.hint_text.customer_phone_number'),
            'delivery_zipcode.regex'=>__('validation.hint_text.customer_zipcode'),
        ];
        $validator = Validator::make($params, [
            'order_status_id' => ['required', 'exists:order_statuses,id'],
            'purchase_route_id' => ['required', 'exists:purchase_routes,id'],
            'display_purchase_warning_flag' => ['required', 'in:0,1'],
            'message_from_customer' => ['nullable', 'string'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'customer_email' => ['required', 'email', "regex:/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/", 'max:255'],
            'customer_advertising_media_code'=>['nullable', 'integer', 'exists:advertising_media,code'],
            'customer_name01'=>['required', 'string', 'max:255'],
            'customer_name02'=>['required', 'string', 'max:255'],
            'customer_kana01'=>['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:255'],
            'customer_kana02'=>['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:255'],
            'customer_phone_number01'=>['required', 'string', 'regex:/^[0-9]+$/', 'max:11'],
            'customer_phone_number02'=>['nullable', 'string', 'regex:/^[0-9]+$/', 'max:11'],
            'customer_zipcode'=>['required', 'string', 'regex:/^[0-9]+$/', 'min:7', 'max:7'],
            'customer_prefecture'=>['required', 'exists:prefectures,id'],
            'customer_address1'=>['required', 'string', 'max:255'],
            'customer_address2'=>['required', 'string', 'max:255'],
            'customer_address3'=>['nullable', 'string', 'max:255'],
            'selected_product_ids'=>['required', 'array', 'exists:products,id'],
            'product_quantity.*'=>['required', 'integer', 'min:1', 'max:32767'],
            'order_subtotal'=>['required', 'integer', 'min:0', 'max:2147483647'],
            'order_delivery_fee'=>['required', 'integer', 'min:0', 'max:2147483647'],
            'order_payment_method_fee'=>['required', 'integer', 'min:0', 'max:2147483647'],
            'order_discount'=>['required', 'integer', 'min:0', 'max:2147483647'],
            'order_payment_total'=>['required', 'integer', 'min:0', 'max:2147483647'],
            'order_payment_total_tax'=>['required', 'integer', 'min:0', 'max:2147483647'],
            'payment_method_id'=>['required', 'exists:payment_methods,id'],
            'order_delivery_id'=>['required', 'exists:deliveries,id'],
            'shippings_requested_delivery_date'=>['nullable','date_format:"Ymd"'],
            'shippings_delivery_time_id'=>['nullable', 'exists:delivery_times,id'],
            'delivery_name01'=>['required', 'string', 'max:255'],
            'delivery_name02'=>['required', 'string', 'max:255'],
            'delivery_kana01'=>['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:255'],
            'delivery_kana02'=>['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:255'],
            'delivery_phone_number01'=>['required', 'string', 'regex:/^[0-9]+$/', 'max:11'],
            'delivery_phone_number02'=>['nullable', 'string', 'regex:/^[0-9]+$/', 'max:11'],
            'delivery_zipcode'=>['required', 'string', 'regex:/^[0-9]+$/', 'min:7', 'max:7'],
            'delivery_prefecture'=>['required', 'exists:prefectures,id'],
            'delivery_address1'=>['required', 'string', 'max:255'],
            'delivery_address2'=>['required', 'string', 'max:255'],
            'delivery_address3'=>['nullable', 'string', 'max:255'],
            'shop_memo_note'=>['nullable', 'string'],
            'shop_memo_important'=>['nullable', 'in:1'],
            'shop_memo_claim_flag'=>['nullable', 'in:1'],
        ], $messages);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $order = $params;

        $sum_subtotal = 0;
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
            $order['selected_product_ids'] = $selected_products;
            $sum_subtotal = $products->sum(DB::raw('products.price * products_quantity.quantity'));
        }

        $tax = app(TaxService::class)->getRate();
        $tax = is_null($tax) || !isset($tax) ? 0 : $tax;

        $delivery_fee = isset($params['order_delivery_fee']) ? $params['order_delivery_fee'] : 0;
        $payment_method_fee = isset($params['order_payment_method_fee']) ? $params['order_payment_method_fee'] : 0;
        $discount = isset($params['order_discount']) ? $params['order_discount'] : 0;

        $payment_total = $sum_subtotal + $delivery_fee + $payment_method_fee - $discount;
        $payment_total_tax = round($payment_total * $tax);
        $order['order_subtotal'] = $sum_subtotal;
        $order['order_payment_total'] = $payment_total;
        $order['order_payment_total_tax'] = $payment_total_tax;

        Cache::forever('order', $order);

        $view_params=[
            'order'=>$order,
            'isPeriodic'=>false,
        ];

        $customer_id = $params['customer_id'];
        $customer = Customer::find($customer_id);
        if (!is_null($customer)) {
            $customerShopMemos = $customer->shopMemos()->whereNull('deleted_by')
                ->whereNull('order_id')->whereNull('periodic_order_id')
                ->orderBy('important', 'desc')
                ->orderBy('claim_flag', 'desc')
                ->orderBy('created_at', 'desc')->get();

            $view_params['customerShopMemos'] = $customerShopMemos;
        }

        if ($isPopup) {
            return view("admin.pages.order.popup_info", $view_params);
        }
        else {
            return view("admin.pages.order.info", $view_params);
        }
    }

    public function createSave(Request $request)
    {
        $params = $request->all();

        $isPopup = isset($params['is_popup']);

        $customer_id = $params["customer_id"];
        $customer = Customer::find($customer_id);

        $createOrderParameters = [];
        $createOrderParameters['order_status_id'] = $params["order_status_id"];
        $createOrderParameters['purchase_route_id'] = $params["purchase_route_id"];
        $createOrderParameters['display_purchase_warning_flag'] = $params["display_purchase_warning_flag"];
        if (isset($params["message_from_customer"])) {
            $createOrderParameters["message_from_customer"] = $params["message_from_customer"];
        }
        $createOrderParameters['order_delivery_fee'] = $params["order_delivery_fee"];
        $createOrderParameters['order_payment_method_fee'] = $params["order_payment_method_fee"];
        $createOrderParameters['order_discount'] = $params["order_discount"];
        $createOrderParameters['payment_method_id'] = $params["payment_method_id"];
        $createOrderParameters['order_delivery_id'] = $params["order_delivery_id"];
        if (isset($params['shop_memo_note'])) {
            $createOrderParameters['shop_memo_note'] = $params["shop_memo_note"];
        }
        if (isset($params['shop_memo_important'])) {
            $createOrderParameters['shop_memo_important'] = $params["shop_memo_important"];
        }
        if (isset($params['shop_memo_claim_flag'])) {
            $createOrderParameters['shop_memo_claim_flag'] = $params["shop_memo_claim_flag"];
        }

        $createOrderDetailParameters = [];
        if (isset($params['selected_product_ids'])) {
            $createOrderDetailParameters['selected_product_ids'] = $params["selected_product_ids"];
            $createOrderDetailParameters['product_quantity'] = $params["product_quantity"];
        }

        $createShippingsParameters = [];
        if (isset($params['shippings_requested_delivery_date'])) {
            $createShippingsParameters['shippings_requested_delivery_date'] = $params['shippings_requested_delivery_date'];
        }
        if (isset($params['shippings_delivery_time_id'])) {
            $createShippingsParameters['shippings_delivery_time_id'] = $params['shippings_delivery_time_id'];
        }
        $createShippingsParameters['delivery_name01'] = $params['delivery_name01'];
        $createShippingsParameters['delivery_name02'] = $params['delivery_name02'];
        $createShippingsParameters['delivery_kana01'] = $params['delivery_kana01'];
        $createShippingsParameters['delivery_kana02'] = $params['delivery_kana02'];
        $createShippingsParameters['delivery_phone_number01'] = $params['delivery_phone_number01'];
        if (isset($params['delivery_phone_number02'])) {
            $createShippingsParameters['delivery_phone_number02'] = $params['delivery_phone_number02'];
        }
        $createShippingsParameters['delivery_zipcode'] = $params['delivery_zipcode'];
        $createShippingsParameters['delivery_prefecture'] = $params['delivery_prefecture'];
        $createShippingsParameters['delivery_address1'] = $params['delivery_address1'];
        $createShippingsParameters['delivery_address2'] = $params['delivery_address2'];
        if (isset($params['delivery_address3'])) {
            $createShippingsParameters['delivery_address3'] = $params['delivery_address3'];
        }

        try {
            $this->service->acceptOrder(
                $customer,
                $createOrderParameters,
                $createOrderDetailParameters,
                $createShippingsParameters,
                Carbon::now());
            $this->toastr->add('success',__("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }

        if ($isPopup) {
            return redirect()->route('admin.order.popup.create', ['customer_id'=>$customer_id]);
        }
        else {
            return redirect()->route('admin.order.create');
        }
    }

    public function edit(Request $request, $id)
    {
        return $this->editIndex($request, $id, "admin.pages.order.edit");
    }

    public function popupEdit(Request $request, $id)
    {
        return $this->editIndex($request, $id, "admin.pages.order.popup_edit");
    }

    protected function editIndex(Request $request, $id, $view_name)
    {
        $params = $request->all();
        $params["id"] = $id;
        $validator = Validator::make($params, [
            'id' => ['required', 'integer', 'exists:orders,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return back();
        }

        if(isset($params['back'])){
            $order = Cache::get('order');
            $view_params=[
                'mode'=>'edit',
                "isPeriodic"=>false,
                "order"=>$order,
            ];

            if (isset($order['order_id'])) {
                $order1 = Order::find($order['order_id']);

                $order['order_count_of_customer'] = $order1->order_count_of_customer;
                $order['order_count_of_customer_without_cancel'] = $order1->order_count_of_customer_without_cancel;
                $order['periodic_order_id'] = $order1->periodic_order_id;

                $customer = $order1->customer;
                $shipping = $order1->shipping;
                $orderShopMemos = $order1->shopMemos()->whereNull('deleted_by')
                    ->orderBy('important', 'desc')
                    ->orderBy('claim_flag', 'desc')
                    ->orderBy('created_at', 'desc')->get();
                $view_params['shipping'] = $shipping;
                $view_params['orderShopMemos'] = $orderShopMemos;

                if (!is_null($customer)) {
                    $customerShopMemos = $customer->shopMemos()->whereNull('deleted_by')
                        ->whereNull('order_id')->whereNull('periodic_order_id')
                        ->orderBy('important', 'desc')
                        ->orderBy('claim_flag', 'desc')
                        ->orderBy('created_at', 'desc')->get();

                    $view_params['customerShopMemos'] = $customerShopMemos;
                }
            }

            return view($view_name, $view_params);
        }
        else {
            $order = Order::find($id);

            $order['order_id'] = $id;
            if (isset($params['isShipping'])) {
                $order['isShipping'] = true;
            }

            $customer = $order->customer;
            $shipping = $order->shipping;
            $orderShopMemos = $order->shopMemos()->whereNull('deleted_by')
                ->orderBy('important', 'desc')
                ->orderBy('claim_flag', 'desc')
                ->orderBy('created_at', 'desc')->get();

            $customerShopMemos = [];
            if (!is_null($customer)) {
                $customerShopMemos = $customer->shopMemos()->whereNull('deleted_by')
                    ->whereNull('order_id')->whereNull('periodic_order_id')
                    ->orderBy('important', 'desc')
                    ->orderBy('claim_flag', 'desc')
                    ->orderBy('created_at', 'desc')->get();
                $order['customer_advertising_media_code'] = $customer->advertising_media_code;
            }

            $order['customer_email'] = $order->email;

            $order['customer_name01'] = $order->name01;
            $order['customer_name02'] = $order->name02;
            $order['customer_kana01'] = $order->kana01;
            $order['customer_kana02'] = $order->kana02;
            $order['customer_phone_number01'] = $order->phone_number01;
            $order['customer_phone_number02'] = $order->phone_number02;
            $order['customer_zipcode'] = $order->zipcode;
            $order['customer_prefecture'] = $order->prefecture_id;
            $order['customer_address1'] = $order->address01;
            $order['customer_address2'] = $order->address02;
            $order['customer_address3'] = $order->requests_for_delivery;

            $selected_product = $order->details()->get()->pluck('quantity', 'product_id')->toArray();
            $order['selected_product_ids'] = array_keys($selected_product);
            $order['product_quantity'] = $selected_product;

            $order['order_delivery_fee'] = $order->delivery_fee;
            $order['order_payment_method_fee'] = $order->payment_method_fee;
            $order['order_discount'] = $order->discount;
            $order['order_payment_total'] = $order->payment_total;
            $order['order_payment_total_tax'] = $order->payment_total_tax;

            $order['order_delivery_id'] = $order->delivery_id;

            if (!is_null($shipping)) {
                $order['shippings_requested_delivery_date'] = $shipping->requested_delivery_date;
                $order['shippings_delivery_time_id'] = $shipping->delivery_time_id;

                $order['delivery_name01'] = $shipping->name01;
                $order['delivery_name02'] = $shipping->name02;
                $order['delivery_kana01'] = $shipping->kana01;
                $order['delivery_kana02'] = $shipping->kana02;
                $order['delivery_phone_number01'] = $shipping->phone_number01;
                $order['delivery_phone_number02'] = $shipping->phone_number02;
                $order['delivery_zipcode'] = $shipping->zipcode;
                $order['delivery_prefecture'] = $shipping->prefecture_id;
                $order['delivery_address1'] = $shipping->address01;
                $order['delivery_address2'] = $shipping->address02;
                $order['delivery_address3'] = $shipping->requests_for_delivery;
            }

            if (!is_null($customer)) {
                $customer_pair_relationships = DB::table('customer_pair_relationships')
                    ->select(DB::raw(sprintf("string_agg((customer_pair_relationships.customer_id_a + customer_pair_relationships.customer_id_b - %d)::character varying, ',') as relationships", $customer->id)))
                    ->join('customers as customers_a', function ($join) {
                        $join->on('customers_a.id', '=', 'customer_pair_relationships.customer_id_a')
                            ->whereNotNull('customers_a.confirmed_timestamp')
                            ->whereNull('customers_a.deleted_at');
                    })
                    ->join('customers as customers_b', function ($join) {
                        $join->on('customers_b.id', '=', 'customer_pair_relationships.customer_id_b')
                            ->whereNotNull('customers_b.confirmed_timestamp')
                            ->whereNull('customers_b.deleted_at');
                    })
                    ->whereRaw('customer_pair_relationships.customer_pair_relationship_type_id in (1, 2, 3)')
                    ->whereRaw(sprintf('%d in (customer_pair_relationships.customer_id_a, customer_pair_relationships.customer_id_b)', $customer->id));

                $customer_pair_relationships = $customer_pair_relationships->first();
                $order['customer_pair_relationships'] = $customer_pair_relationships->relationships;

                $order_bundle_shippings = DB::table('order_bundle_shippings')
                    ->select(DB::raw(sprintf("string_agg((order_bundle_shippings.order_id + order_bundle_shippings.parent_order_id - %d)::character varying, ',') as bundles", $id)))
                    ->join('orders as child_order', function ($join) {
                        $join->on('child_order.id', '=', 'order_bundle_shippings.order_id')
                            ->whereNotNull('child_order.confirmed_timestamp')
                            ->whereNull('child_order.deleted_at');
                    })
                    ->join('orders as parent_order', function ($join) {
                        $join->on('parent_order.id', '=', 'order_bundle_shippings.parent_order_id')
                            ->whereNotNull('parent_order.confirmed_timestamp')
                            ->whereNull('parent_order.deleted_at');
                    })
                    ->whereRaw(sprintf('%d in (order_bundle_shippings.order_id, order_bundle_shippings.parent_order_id)', $id));

                $order_bundle_shippings = $order_bundle_shippings->first();
                $order['order_bundle_shippings'] = $order_bundle_shippings->bundles;
            }

            $view_params=[
                'mode'=>'edit',
                "isPeriodic"=>false,
                "order"=>$order,
                "shipping"=>$shipping,
                "orderShopMemos"=>$orderShopMemos,
                "customerShopMemos"=>$customerShopMemos
            ];

            return view($view_name, $view_params);
        }
    }

    public function editInfo(Request $request)
    {
        $params = $request->all();

        $isPopup = isset($params['is_popup']);

        if (isset($params['update_type'])) {
            if ($params['update_type'] == 'paymentUpdate') {
                return $this->orderPaymentUpdate($request);
            }
        }

        $messages = [
            'updated_at.exists' => __('validation.hint_text.aleady_modified'),
            'order_id' => __('validation.hint_text.not_exist_deleted'),
            'customer_email.regex' => __('validation.email'),
            'customer_kana01.regex'=>__('validation.hint_text.customer_kana'),
            'customer_kana02.regex'=>__('validation.hint_text.customer_kana'),
            'customer_phone_number01.regex'=>__('validation.hint_text.customer_phone_number'),
            'customer_phone_number02.regex'=>__('validation.hint_text.customer_phone_number'),
            'customer_zipcode.regex'=>__('validation.hint_text.customer_zipcode'),
            'delivery_kana01.regex'=>__('validation.hint_text.customer_kana'),
            'delivery_kana02.regex'=>__('validation.hint_text.customer_kana'),
            'delivery_phone_number01.regex'=>__('validation.hint_text.customer_phone_number'),
            'delivery_phone_number02.regex'=>__('validation.hint_text.customer_phone_number'),
            'delivery_zipcode.regex'=>__('validation.hint_text.customer_zipcode'),
        ];
        $validator = Validator::make($params, [
            'order_id' => ['required', 'exists:orders,id,deleted_at,NULL'],
            'updated_at' => ['required',  'exists:orders,updated_at,id,'.$params['order_id']],
            'order_status_id' => ['required', 'exists:order_statuses,id'],
            'purchase_route_id' => ['required', 'exists:purchase_routes,id'],
            'display_purchase_warning_flag' => ['required', 'in:0,1'],
            'message_from_customer' => ['nullable', 'string'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'customer_email' => ['required', 'email', "regex:/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/", 'max:255'],
            'customer_advertising_media_code'=>['nullable', 'integer', 'exists:advertising_media,code'],
            'customer_name01'=>['required', 'string', 'max:255'],
            'customer_name02'=>['required', 'string', 'max:255'],
            'customer_kana01'=>['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:255'],
            'customer_kana02'=>['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:255'],
            'customer_phone_number01'=>['required', 'string', 'regex:/^[0-9]+$/', 'max:11'],
            'customer_phone_number02'=>['nullable', 'string', 'regex:/^[0-9]+$/', 'max:11'],
            'customer_zipcode'=>['required', 'string', 'regex:/^[0-9]+$/', 'min:7', 'max:7'],
            'customer_prefecture'=>['required', 'exists:prefectures,id'],
            'customer_address1'=>['required', 'string', 'max:255'],
            'customer_address2'=>['required', 'string', 'max:255'],
            'customer_address3'=>['nullable', 'string', 'max:255'],
            'selected_product_ids'=>['required', 'array', 'exists:products,id'],
            'product_quantity.*'=>['required', 'integer', 'min:1', 'max:32767'],
            'order_subtotal'=>['required', 'integer', 'min:0'],
            'order_delivery_fee'=>['required', 'integer', 'min:0'],
            'order_payment_method_fee'=>['required', 'integer', 'min:0'],
            'order_discount'=>['required', 'integer', 'min:0'],
            'order_payment_total'=>['required', 'integer', 'min:0'],
            'order_payment_total_tax'=>['required', 'integer', 'min:0'],
            'payment_method_id'=>['required', 'exists:payment_methods,id'],
            'order_delivery_id'=>['required', 'exists:deliveries,id'],
            'shippings_requested_delivery_date'=>['nullable','date_format:"Y-m-d"'],
            'shippings_delivery_time_id'=>['nullable', 'exists:delivery_times,id'],
            'delivery_name01'=>['required', 'string', 'max:255'],
            'delivery_name02'=>['required', 'string', 'max:255'],
            'delivery_kana01'=>['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:255'],
            'delivery_kana02'=>['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:255'],
            'delivery_phone_number01'=>['required', 'string', 'regex:/^[0-9]+$/', 'max:11'],
            'delivery_phone_number02'=>['nullable', 'string', 'regex:/^[0-9]+$/', 'max:11'],
            'delivery_zipcode'=>['required', 'string', 'regex:/^[0-9]+$/', 'min:7', 'max:7'],
            'delivery_prefecture'=>['required', 'exists:prefectures,id'],
            'delivery_address1'=>['required', 'string', 'max:255'],
            'delivery_address2'=>['required', 'string', 'max:255'],
            'delivery_address3'=>['nullable', 'string', 'max:255'],
            'shop_memo_note'=>['nullable', 'string'],
            'shop_memo_important'=>['nullable', 'in:1'],
            'shop_memo_claim_flag'=>['nullable', 'in:1'],
        ], $messages);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $order = $params;

        $sum_subtotal = 0;
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
            $order['selected_product_ids'] = $selected_products;
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
        $order['order_subtotal'] = $sum_subtotal;
        $order['order_payment_total'] = $payment_total;
        $order['order_payment_total_tax'] = $payment_total_tax;

        if (isset($params['isShipping'])) {
            $order['isShipping'] = true;
        }

        Cache::forever('order', $order);

        $order1 = Order::find($params['order_id']);
        $orderShopMemos = $order1->shopMemos()->whereNull('deleted_by')
            ->orderBy('important', 'desc')
            ->orderBy('claim_flag', 'desc')
            ->orderBy('created_at', 'desc')->get();

        $customer_id = $params['customer_id'];
        $customer = Customer::find($customer_id);
        $customerShopMemos = [];
        if (!is_null($customer)) {
            $customerShopMemos = $customer->shopMemos()->whereNull('deleted_by')
                ->whereNull('order_id')->whereNull('periodic_order_id')
                ->orderBy('important', 'desc')
                ->orderBy('claim_flag', 'desc')
                ->orderBy('created_at', 'desc')->get();
        }

        $view_params = [
            'order'=>$order,
            'orderShopMemos'=>$orderShopMemos,
            'customerShopMemos'=>$customerShopMemos,
            "isPeriodic"=>false,
        ];

        if ($isPopup) {
            return view("admin.pages.order.popup_info", $view_params);
        }
        else {
            return view("admin.pages.order.info", $view_params);
        }
    }

    public function orderPaymentUpdate(Request $request)
    {
        $params = $request->all();
        $messages = [
            'updated_at.exists' => __('validation.hint_text.aleady_modified'),
            'order_id' => __('validation.hint_text.not_exist_deleted'),
        ];

        $validator = Validator::make($params, [
            'order_id' => ['required', 'exists:orders,id,deleted_at,NULL'],
            'updated_at' => ['required',  'exists:orders,updated_at,id,'.$params['order_id']],
            'settlement_card_id'=>['required_if:payment_method_id,==,5',],
        ],$messages);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $order_id = $params['order_id'];

        try {
            $result = $this->service->orderPaymentUpdate($params);
            if ($result->getResult() == 0) {
                $this->toastr->add('success',__("common.response.success"));
            }
            else {
                //$responseData->saved = $result->responseCode;
                $this->toastr->add('warning', $result->responseDetail);
                return Redirect::back()->withInput();
            }
        } catch (\Exception $e) {
            $this->toastr->add('warning', $e->getMessage());
            return Redirect::back()->withInput();
        }

        $isPopup = isset($params['is_popup']);

        if ($isPopup) {
            return redirect()->route('admin.order.popup.edit', [$order_id]);
        }
        else {
            return redirect()->route('admin.order.edit', [$order_id]);
        }
    }

    public function editSave(Request $request)
    {
        $params = $request->all();

        $isPopup = isset($params['is_popup']);

        $customer_id = $params["customer_id"];
        $customer = Customer::find($customer_id);

        $createOrderParameters = [];
        $createOrderParameters['order_id'] = $params["order_id"];
        $createOrderParameters['order_status_id'] = $params["order_status_id"];
        $createOrderParameters['purchase_route_id'] = $params["purchase_route_id"];
        $createOrderParameters['display_purchase_warning_flag'] = $params["display_purchase_warning_flag"];
        if (isset($params["message_from_customer"])) {
            $createOrderParameters["message_from_customer"] = $params["message_from_customer"];
        }
        $createOrderParameters['order_delivery_fee'] = $params["order_delivery_fee"];
        $createOrderParameters['order_payment_method_fee'] = $params["order_payment_method_fee"];
        $createOrderParameters['order_discount'] = $params["order_discount"];
        $createOrderParameters['payment_method_id'] = $params["payment_method_id"];
        $createOrderParameters['order_delivery_id'] = $params["order_delivery_id"];
        $createOrderParameters['tax_rate'] = $params["tax_rate"];
        $createOrderParameters['order_summary_disable'] = $params["order_summary_disable"];
        if (isset($params['shop_memo_note'])) {
            $createOrderParameters['shop_memo_note'] = $params["shop_memo_note"];
        }
        if (isset($params['shop_memo_important'])) {
            $createOrderParameters['shop_memo_important'] = $params["shop_memo_important"];
        }
        if (isset($params['shop_memo_claim_flag'])) {
            $createOrderParameters['shop_memo_claim_flag'] = $params["shop_memo_claim_flag"];
        }

        $createOrderDetailParameters = [];
        if (isset($params['selected_product_ids'])) {
            $createOrderDetailParameters['selected_product_ids'] = $params["selected_product_ids"];
            $createOrderDetailParameters['product_quantity'] = $params["product_quantity"];
        }

        $createShippingsParameters = [];
        if (isset($params['shippings_requested_delivery_date'])) {
            $createShippingsParameters['shippings_requested_delivery_date'] = $params['shippings_requested_delivery_date'];
        }
        if (isset($params['shippings_delivery_time_id'])) {
            $createShippingsParameters['shippings_delivery_time_id'] = $params['shippings_delivery_time_id'];
        }
        $createShippingsParameters['delivery_name01'] = $params['delivery_name01'];
        $createShippingsParameters['delivery_name02'] = $params['delivery_name02'];
        $createShippingsParameters['delivery_kana01'] = $params['delivery_kana01'];
        $createShippingsParameters['delivery_kana02'] = $params['delivery_kana02'];
        $createShippingsParameters['delivery_phone_number01'] = $params['delivery_phone_number01'];
        if (isset($params['delivery_phone_number02'])) {
            $createShippingsParameters['delivery_phone_number02'] = $params['delivery_phone_number02'];
        }
        $createShippingsParameters['delivery_zipcode'] = $params['delivery_zipcode'];
        $createShippingsParameters['delivery_prefecture'] = $params['delivery_prefecture'];
        $createShippingsParameters['delivery_address1'] = $params['delivery_address1'];
        $createShippingsParameters['delivery_address2'] = $params['delivery_address2'];
        if (isset($params['delivery_address3'])) {
            $createShippingsParameters['delivery_address3'] = $params['delivery_address3'];
        }

        try {
            $this->service->acceptOrder(
                $customer,
                $createOrderParameters,
                $createOrderDetailParameters,
                $createShippingsParameters,
                Carbon::now());
            $this->toastr->add('success',__("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }

        if ($isPopup) {
            return redirect()->route('admin.order.popup.edit', [$createOrderParameters['order_id']]);
        }
        else {
            if (isset($params['isShipping'])) {
                return redirect()->route('admin.order.edit', [$createOrderParameters['order_id'], 'isShipping'=>true]);
            }
            else {
                return redirect()->route('admin.order.edit', [$createOrderParameters['order_id']]);
            }
        }
    }

    public function update(Request $request)
    {
        $responseData = new ApiResponseData($request);

        try {
            $responseData = $this->service->update($request);
        }
        catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }

    public function delete(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'delete_chk_list' => ['required', 'array', 'exists:orders,id'],
        ]);

        $responseData = new ApiResponseData($request);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        try {
            $this->service->delete($params);
            $responseData->message = __("common.response.success");
            $responseData->status = "success";
        } catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }

    public function productSummary(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'selected_product_ids'=>['nullable', 'array', 'exists:products,id'],
            'product_quantity.*'=>['nullable', 'min:1', 'max:32767'],
            'order_delivery_fee'=>['nullable', 'integer', 'min:0'],
            'order_payment_method_fee'=>['nullable', 'integer', 'min:0'],
            'order_discount'=>['nullable', 'integer', 'min:0'],
            'order_payment_total'=>['nullable', 'integer', 'min:0'],
            'order_payment_total_tax'=>['nullable', 'integer', 'min:0'],
        ]);

        $responseData = new ApiResponseData($request);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        try {
            $summary = $this->service->productSummary($params);
            if (!isset($params['unnotify'])) {
                $responseData->message = __("common.response.success");
            }
            $responseData->saved = $summary;
            $responseData->status = "success";
        } catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }


    public function sendTelegramCreditCommitRevise(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'order_id' => ['required', 'exists:orders,id,deleted_at,NULL'],
        ]);

        $responseData = new ApiResponseData($request);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        try {
            $result = $this->service->sendTelegramCreditCommitRevise(
                $params['order_id'],
                isset($params['settlement_id']) ? $params['settlement_id'] : null,
                0
            );
            $responseData->saved = $result;
            if ($result['result'] == 0) {
                $responseData->message = __("common.response.success");
                $responseData->status = "success";
            }
            else {
                $responseData->message = $result->settlement_sub_response_detail;
                $responseData->status = "warning";
            }
        } catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }

    public function sendTelegramCreditCommitCancel(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'order_id' => ['required', 'exists:orders,id,deleted_at,NULL'],
        ]);

        $responseData = new ApiResponseData($request);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        try {
            $result = $this->service->sendTelegramCreditCommitCancel(
                $params['order_id'],
                isset($params['settlement_id']) ? $params['settlement_id'] : null
            );

            $responseData->saved = $result;
            if ($result['result'] == 0) {
                $responseData->message = __("common.response.success");
                $responseData->status = "success";
            }
            else {
                $responseData->message = $result->settlement_sub_response_detail;
                $responseData->status = "warning";
            }

        } catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }

    public function sendTelegramCreditStockDelete(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'customer_id' => ['required', 'exists:customers,id,deleted_at,NULL'],
        ]);

        $responseData = new ApiResponseData($request);

        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        try {
            $result = $this->service->sendTelegramCreditStockDelete(
                $params['customer_id'],
                isset($params['settlement_card_id']) ? $params['settlement_card_id'] : null
            );

            $responseData->saved = $result;
            if ($result['result'] == 0) {
                $responseData->message = __("common.response.success");
                $responseData->status = "success";
            }
            else {
                $responseData->message = $result->response_detail;
                $responseData->status = "warning";
            }

        } catch (\Exception $e) {
            $responseData->message = $e->getMessage();
            $responseData->status = "error";
        }

        return response()->json($responseData);
    }
}
