<?php

namespace App\Http\Controllers\Admin\Order;

use App\Models\Customer;
use App\Services\FlashToastrMessageService;
use App\Services\PeriodicService;
use App\Services\TaxService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\PeriodicOrder;
use App\Models\Exports\PeriodicOrderSearchResultExport;
use App\Http\Controllers\Admin\BaseController;
use App\Common\Api\ApiResponseData;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PeriodicController extends BaseController
{
    private $service;
    private $toastr;

    public function __construct()
    {
        $this->service = app(PeriodicService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    public function search(Request $request)
    {
        $view_params = [
            "orders"=>[],
            "deletable"=>false,
        ];

        $params = $request->all();
        if(isset($params['back'])) {
            $search_params = Cache::get('periodic_search_params');

            $search_params['account'] = Admin::find($request->user()->id);

            return $this->getSearchResult($search_params);
        }

        return view("admin.pages.periodic.search",$view_params);
    }

    public function searchResult(Request $request)
    {
        $params = $request->all();

        $messages = [
            'periodic_order_customer_kana.regex'=>__('validation.hint_text.customer_kana'),
        ];
        $validator = Validator::make($params, [
            'periodic_order_id' => ['nullable', 'integer', 'min:1', 'max:2147483647'],
            'periodic_order_customer_id' => ['nullable', 'integer', 'min:1', 'max:2147483647'],
            'periodic_order_customer_name' => ['nullable', 'string', 'max:255'],
            'periodic_order_customer_kana' => ['nullable', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:255'],
            'payment_method_id' => ['nullable', 'array', 'exists:payment_methods,id'],
            'periodic_order_failed_flag' => ['nullable', 'in:0,1'],
            'periodic_order_stop_flag' => ['nullable', 'in:0,1'],
            'periodic_count' => ['nullable', 'integer', 'min:0', 'max:32767'],
            'item_lineup_id' => ['nullable', 'array', 'exists:item_lineups,id'],
            'periodic_order_last_delivery_date_from' => ['nullable', 'date'],
            'periodic_order_last_delivery_date_to' => 'nullable|date'. (isset($params['periodic_order_last_delivery_date_from']) ? '|after_or_equal:periodic_order_last_delivery_date_from' : ''),
            'periodic_order_next_delivery_date_from' => ['nullable', 'date'],
            'periodic_order_next_delivery_date_to' => 'nullable|date'. (isset($params['periodic_order_next_delivery_date_from']) ? '|after_or_equal:periodic_order_next_delivery_date_from' : ''),
        ], $messages);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $params['account'] = Admin::find($request->user()->id);

        return $this->getSearchResult($params);
    }

    public function getSearchResult($params)
    {
        $periodic_order_details = DB::table('periodic_order_details')
            ->select('periodic_order_details.periodic_order_id as periodic_order_id', DB::raw("string_agg(periodic_order_details.product_name, '<br>') as product_names"))
            ->whereRaw('products.undelivered_summary_classification_id <> 3')
            ->groupby('periodic_order_details.periodic_order_id')
            ->join('products', 'products.id', 'periodic_order_details.product_id');

        $periodic_shop_memos = DB::table('shop_memos')
            ->select('shop_memos.periodic_order_id as periodic_order_id', DB::raw("string_agg(shop_memos.note, '<br>' order by shop_memos.updated_at desc) as shop_memo_notes"))
            ->groupby('shop_memos.periodic_order_id');

        $orders = DB::table('periodic_orders')->whereNull('periodic_orders.deleted_at')
            ->select(
                'periodic_orders.id',
                'periodic_orders.periodic_count',
                'periodic_orders.customer_id',
                'periodic_orders.name01',
                'periodic_orders.name02',
                'periodic_order_products.product_names as product_names',
                'periodic_orders.payment_method_id',
                'periodic_orders.last_payment_total',
                'periodic_orders.periodic_interval_type_id',
                'periodic_orders.interval_days',
                'periodic_orders.interval_month',
                'periodic_orders.interval_date_of_month',
                'periodic_orders.last_delivery_date',
                'periodic_orders.next_delivery_date',
                'periodic_orders.failed_flag',
                'periodic_orders.stop_flag',
                'periodic_orders.duplication_warning_flag',
                'periodic_shop_memos.shop_memo_notes')
            ->leftJoin(DB::raw(sprintf('(%s) as periodic_order_products', $periodic_order_details->toSql())), 'periodic_order_products.periodic_order_id', 'periodic_orders.id')
            ->leftJoin(DB::raw(sprintf('(%s) as periodic_shop_memos', $periodic_shop_memos->toSql())), 'periodic_shop_memos.periodic_order_id', 'periodic_orders.id');

        $search_params = $this->service->search_result($orders, $params);

        $number_per_page = $search_params['number_per_page'];
        $orders = $orders->paginate($number_per_page, ['*'], 'page', isset($params['page']) ? $params['page'] : null);

        $account = $params['account'];
        $deletable = $account !== null && in_array($account->admin_role_id, [1, 2]);

        $view_params = [
            "search_params"=>$search_params,
            "orders"=>$orders,
            "deletable"=>$deletable,
        ];

        Cache::forever('periodic_search_params', $search_params);

        return view("admin.pages.periodic.search", $view_params);
    }

    public function downloadCSV(Request $request)
    {
        $params = $request->all();
        $messages = [
            'periodic_order_customer_kana.regex'=>__('validation.hint_text.customer_kana'),
        ];
        $validator = Validator::make($params, [
            'periodic_order_id' => ['nullable', 'integer', 'min:1', 'max:2147483647'],
            'periodic_order_customer_id' => ['nullable', 'integer', 'min:1', 'max:2147483647'],
            'periodic_order_customer_name' => ['nullable', 'string', 'max:255'],
            'periodic_order_customer_kana' => ['nullable', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:255'],
            'payment_method_id' => ['nullable', 'array', 'exists:payment_methods,id'],
            'periodic_order_failed_flag' => ['nullable', 'in:0,1'],
            'periodic_order_stop_flag' => ['nullable', 'in:0,1'],
            'periodic_count' => ['nullable', 'integer', 'min:0', 'max:32767'],
            'item_lineup_id' => ['nullable', 'array', 'exists:item_lineups,id'],
            'periodic_order_last_delivery_date_from' => ['nullable', 'date'],
            'periodic_order_last_delivery_date_to' => 'nullable|date'. (isset($params['periodic_order_last_delivery_date_from']) ? '|after_or_equal:periodic_order_last_delivery_date_from' : ''),
            'periodic_order_next_delivery_date_from' => ['nullable', 'date'],
            'periodic_order_next_delivery_date_to' => 'nullable|date'. (isset($params['periodic_order_next_delivery_date_from']) ? '|after_or_equal:periodic_order_next_delivery_date_from' : ''),
        ], $messages);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $orders = DB::table('periodic_orders')->whereNull('periodic_orders.deleted_at');

        $this->service->search_result($orders, $params);

        $periodicOrderSearchResultExport = app(PeriodicOrderSearchResultExport::class, ['query'=>$orders]);
        return $periodicOrderSearchResultExport->download();
    }

    public function create(Request $request)
    {
        return $this->createIndex($request, "admin.pages.periodic.create");
    }

    public function popupCreate(Request $request)
    {
        return $this->createIndex($request, "admin.pages.periodic.popup_create");
    }

    protected function createIndex(Request $request, $view_name)
    {
        $params = $request->all();
        if(isset($params['back'])){
            $order = Cache::get('periodic_order');

            $view_params=[
                'order'=>$order,
                'mode'=>'create',
                "isPeriodic"=>true,
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
                "isPeriodic"=>true,
            ];

            if (isset($params['customer_id'])) {
                $customer_id = $params['customer_id'];
                $customer = Customer::find($customer_id);
                if (!is_null($customer)) {
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
        ];
        $validator = Validator::make($params, [
            'periodic_interval_type_id' => ['required', 'in:1,2'],
            'interval_days'=>['nullable', 'required_if:periodic_interval_type_id,1', 'integer', 'min:10', 'max:120'],
            'interval_month'=>['nullable', 'required_if:periodic_interval_type_id,2', 'integer', 'min:1', 'max:6'],
            'interval_date_of_month'=>['nullable', 'required_if:periodic_interval_type_id,2', 'integer', 'min:1', 'max:28'],
            'next_delivery_date'=>['nullable', 'date', 'after:' . date('Y-m-d')],
            'periodic_failed_flag' => ['required', 'in:0,1'],
            'periodic_stop_flag' => ['required', 'in:0,1'],
            'purchase_route_id' => ['required', 'exists:purchase_routes,id'],
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
            'settlement_card_id'=>['nullable', 'string', 'max:255'],
            'settlement_masked_card_number'=>['nullable', 'string', 'max:255'],
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
            'shippings_delivery_time_id'=>['nullable', 'date_format:"H:i"'],
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

        $periodic = $params;

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
            $periodic['selected_product_ids'] = $selected_products;
            $sum_subtotal = $products->sum(DB::raw('products.price * products_quantity.quantity'));
        }

        $tax = app(TaxService::class)->getRate();
        $tax = is_null($tax) || !isset($tax) ? 0 : $tax;

        $delivery_fee = isset($params['order_delivery_fee']) ? $params['order_delivery_fee'] : 0;
        $payment_method_fee = isset($params['order_payment_method_fee']) ? $params['order_payment_method_fee'] : 0;
        $discount = isset($params['order_discount']) ? $params['order_discount'] : 0;

        $payment_total = $sum_subtotal + $delivery_fee + $payment_method_fee - $discount;
        $payment_total_tax = round($payment_total * $tax);
        $periodic['order_subtotal'] = $sum_subtotal;
        $periodic['order_payment_total'] = $payment_total;
        $periodic['order_payment_total_tax'] = $payment_total_tax;

        Cache::forever('periodic_order', $periodic);

        $view_params=[
            'order'=>$periodic,
            'isPeriodic'=>true,
        ];

        $customer_id = $params['customer_id'];
        $customer = Customer::find($customer_id);
        if (!is_null($customer)) {
            $customerShopMemos = $customer->shopMemos()->whereNull('deleted_by')
                ->whereNull('order_id')->whereNull('periodic_order_id')
                ->orderBy('important', 'desc')
                ->orderBy('claim_flag', 'desc')
                ->orderBy('created_at', 'desc')->get();

            $view_params["customerShopMemos"] = $customerShopMemos;
        }

        if ($isPopup) {
            return view("admin.pages.periodic.popup_info", $view_params);
        }
        else {
            return view("admin.pages.periodic.info", $view_params);
        }
    }

    public function createSave(Request $request)
    {
        $params = $request->all();

        $isPopup = isset($params['is_popup']);

        $customer_id = $params["customer_id"];
        $customer = Customer::find($customer_id);

        $createOrderParameters = [];
        $createOrderParameters['periodic_interval_type_id'] = $params["periodic_interval_type_id"];
        if ($params["periodic_interval_type_id"] == 1) {
            $createOrderParameters['interval_days'] = $params["interval_days"];
        }
        else {
            $createOrderParameters['interval_month'] = $params["interval_month"];
            $createOrderParameters['interval_date_of_month'] = $params["interval_date_of_month"];
        }
        if (isset($params["next_delivery_date"])) {
            $createOrderParameters["next_delivery_date"] = $params["next_delivery_date"];
        }
        $createOrderParameters['periodic_failed_flag'] = $params["periodic_failed_flag"];
        $createOrderParameters['periodic_stop_flag'] = $params["periodic_stop_flag"];
        $createOrderParameters['purchase_route_id'] = $params["purchase_route_id"];
        if (isset($params["message_from_customer"])) {
            $createOrderParameters["message_from_customer"] = $params["message_from_customer"];
        }
        if (isset($params["settlement_card_id"])) {
            $createOrderParameters["settlement_card_id"] = $params["settlement_card_id"];
        }
        if (isset($params["settlement_masked_card_number"])) {
            $createOrderParameters["settlement_masked_card_number"] = $params["settlement_masked_card_number"];
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
            dd($e->getMessage());
        }

        if ($isPopup) {
            return redirect()->route('admin.periodic.popup.create', ['customer_id'=>$customer_id]);
        }
        else {
            return redirect()->route('admin.periodic.create');
        }
    }

    public function edit(Request $request, $id)
    {
        return $this->editIndex($request, $id, "admin.pages.periodic.edit");
    }

    public function popupEdit(Request $request, $id)
    {
        return $this->editIndex($request, $id, "admin.pages.periodic.popup_edit");
    }

    protected function editIndex(Request $request, $id, $view_name)
    {
        $params = $request->all();
        $params["id"] = $id;
        $validator = Validator::make($params, [
            'id' => ['required', 'integer', 'exists:periodic_orders,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return back();
        }

        if(isset($params['back'])){
            $periodic = Cache::get('periodic_order');
            $view_params=[
                'mode'=>'edit',
                "isPeriodic"=>true,
                "order"=>$periodic,
            ];

            if (isset($periodic['order_id'])) {
                $order1 = PeriodicOrder::find($periodic['order_id']);

                $customer = $order1->customer;
                $shipping = $order1->shipping;

                $view_params['shipping'] = $shipping;

                $orderShopMemos = $order1->shopMemos()->whereNull('deleted_by')
                    ->orderBy('important', 'desc')
                    ->orderBy('claim_flag', 'desc')
                    ->orderBy('created_at', 'desc')->get();

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
            $periodic = PeriodicOrder::find($id);

            $periodic['order_id'] = $id;

            $customer = $periodic->customer;
            $shipping = $periodic->shipping;

            $orderShopMemos = $periodic->shopMemos()->whereNull('deleted_by')
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
                $periodic['customer_advertising_media_code'] = $customer->advertising_media_code;
            }

            $periodic['periodic_failed_flag'] = $periodic->failed_flag;
            $periodic['periodic_stop_flag'] = $periodic->stop_flag;

            $periodic['customer_email'] = $periodic->email;

            $periodic['customer_name01'] = $periodic->name01;
            $periodic['customer_name02'] = $periodic->name02;
            $periodic['customer_kana01'] = $periodic->kana01;
            $periodic['customer_kana02'] = $periodic->kana02;
            $periodic['customer_phone_number01'] = $periodic->phone_number01;
            $periodic['customer_phone_number02'] = $periodic->phone_number02;
            $periodic['customer_zipcode'] = $periodic->zipcode;
            $periodic['customer_prefecture'] = $periodic->prefecture_id;
            $periodic['customer_address1'] = $periodic->address01;
            $periodic['customer_address2'] = $periodic->address02;
            $periodic['customer_address3'] = $periodic->requests_for_delivery;

            $selected_product = $periodic->details()->get()->pluck('quantity', 'product_id')->toArray();
            $periodic['selected_product_ids'] = array_keys($selected_product);
            $periodic['product_quantity'] = $selected_product;

            $periodic['order_delivery_fee'] = $periodic->last_delivery_fee;
            $periodic['order_payment_method_fee'] = $periodic->last_payment_method_fee;
            $periodic['order_discount'] = $periodic->discount;
            $periodic['order_payment_total'] = $periodic->last_payment_total;
            $periodic['order_payment_total_tax'] = $periodic->last_payment_total_tax;

            $periodic['order_delivery_id'] = $periodic->last_delivery_id;
            $periodic['settlement_masked_card_number'] = $periodic->settlement_masked_card_number;

            if (!is_null($shipping)) {
                $periodic['shippings_requested_delivery_date'] = $shipping->requested_delivery_date;
                $periodic['shippings_delivery_time_id'] = $shipping->requested_delivery_time;

                $periodic['delivery_name01'] = $shipping->name01;
                $periodic['delivery_name02'] = $shipping->name02;
                $periodic['delivery_kana01'] = $shipping->kana01;
                $periodic['delivery_kana02'] = $shipping->kana02;
                $periodic['delivery_phone_number01'] = $shipping->phone_number01;
                $periodic['delivery_phone_number02'] = $shipping->phone_number02;
                $periodic['delivery_zipcode'] = $shipping->zipcode;
                $periodic['delivery_prefecture'] = $shipping->prefecture_id;
                $periodic['delivery_address1'] = $shipping->address01;
                $periodic['delivery_address2'] = $shipping->address02;
                $periodic['delivery_address3'] = $shipping->requests_for_delivery;
            }

            $view_params=[
                'mode'=>'edit',
                "isPeriodic"=>true,
                "order"=>$periodic,
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
        ];
        $validator = Validator::make($params, [
            'order_id' => ['required', 'exists:periodic_orders,id,deleted_at,NULL'],
            'updated_at' => ['required',  'exists:periodic_orders,updated_at,id,'.$params['order_id']],
            'periodic_interval_type_id' => ['required', 'in:1,2'],
            'interval_days'=>['nullable', 'required_if:periodic_interval_type_id,1', 'integer', 'min:10', 'max:120'],
            'interval_month'=>['nullable', 'required_if:periodic_interval_type_id,2', 'integer', 'min:1', 'max:6'],
            'interval_date_of_month'=>['nullable', 'required_if:periodic_interval_type_id,2', 'integer', 'min:1', 'max:28'],
            'next_delivery_date'=>['nullable', 'date', 'after:' . date('Y-m-d')],
            'periodic_failed_flag' => ['required', 'in:0,1'],
            'periodic_stop_flag' => ['required', 'in:0,1'],
            'purchase_route_id' => ['required', 'exists:purchase_routes,id'],
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
            'settlement_card_id'=>['nullable', 'string', 'max:255'],
            'settlement_masked_card_number'=>['nullable', 'string', 'max:255'],
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
            'shippings_delivery_time_id'=>['nullable', 'date_format:"H:i"'],
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

        $periodic = $params;

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
            $periodic['selected_product_ids'] = $selected_products;
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
        $periodic['order_subtotal'] = $sum_subtotal;
        $periodic['order_payment_total'] = $payment_total;
        $periodic['order_payment_total_tax'] = $payment_total_tax;

        Cache::forever('periodic_order', $periodic);

        $order1 = PeriodicOrder::find($params['order_id']);
        $customer_id = $params['customer_id'];
        $customer = Customer::find($customer_id);
        $orderShopMemos = $order1->shopMemos()->whereNull('deleted_by')
            ->orderBy('important', 'desc')
            ->orderBy('claim_flag', 'desc')
            ->orderBy('created_at', 'desc')->get();

        $view_params = [
            'order'=>$periodic,
            'orderShopMemos'=>$orderShopMemos,
            "isPeriodic"=>true,
        ];

        if (is_null($customer)) {
            $customerShopMemos = $customer->shopMemos()->whereNull('deleted_by')
                ->whereNull('order_id')->whereNull('periodic_order_id')
                ->orderBy('important', 'desc')
                ->orderBy('claim_flag', 'desc')
                ->orderBy('created_at', 'desc')->get();

            $customer['customerShopMemos'] = $customerShopMemos;
        }

        if ($isPopup) {
            return view("admin.pages.periodic.popup_info", $view_params);
        }
        else {
            return view("admin.pages.periodic.info", $view_params);
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
        $createOrderParameters['periodic_interval_type_id'] = $params["periodic_interval_type_id"];
        if ($params["periodic_interval_type_id"] == 1) {
            $createOrderParameters['interval_days'] = $params["interval_days"];
        }
        else {
            $createOrderParameters['interval_month'] = $params["interval_month"];
            $createOrderParameters['interval_date_of_month'] = $params["interval_date_of_month"];
        }
        if (isset($params["next_delivery_date"])) {
            $createOrderParameters["next_delivery_date"] = $params["next_delivery_date"];
        }
        $createOrderParameters['periodic_failed_flag'] = $params["periodic_failed_flag"];
        $createOrderParameters['periodic_stop_flag'] = $params["periodic_stop_flag"];
        $createOrderParameters['purchase_route_id'] = $params["purchase_route_id"];
        if (isset($params["message_from_customer"])) {
            $createOrderParameters["message_from_customer"] = $params["message_from_customer"];
        }
        if (isset($params["settlement_card_id"])) {
            $createOrderParameters["settlement_card_id"] = $params["settlement_card_id"];
        }
        if (isset($params["settlement_masked_card_number"])) {
            $createOrderParameters["settlement_masked_card_number"] = $params["settlement_masked_card_number"];
        }
        $createOrderParameters['order_delivery_fee'] = $params["order_delivery_fee"];
        $createOrderParameters['order_payment_method_fee'] = $params["order_payment_method_fee"];
        $createOrderParameters['order_discount'] = $params["order_discount"];
        $createOrderParameters['payment_method_id'] = $params["payment_method_id"];
        $createOrderParameters['order_delivery_id'] = $params["order_delivery_id"];
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
            return redirect()->route('admin.periodic.popup.edit', [$createOrderParameters['order_id']]);
        }
        else {
            return redirect()->route('admin.periodic.edit', [$createOrderParameters['order_id']]);
        }
    }

    public function delete(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'id' => ['required', 'exists:periodic_orders,id'],
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
}
