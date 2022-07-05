<?php

namespace App\Http\Controllers\Admin\Product;

use App\Exceptions\InvalidDataStateException;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin;
use App\Models\Product;
use App\Services\FlashToastrMessageService;
use App\Services\ProductService;
use App\Common\Api\ApiResponseData;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ProductController extends BaseController
{
    /**
     * @var Service
     */
    protected $service;
    private $toastr;

    /**
     * AccountController constructor.
     */
    public function __construct()
    {
        $this->service = app(ProductService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    public function search(Request $request)
    {
        $products = [];

        $account = Admin::find($request->user()->id);
        $editable = $account !== null && ($account->admin_role_id == 1 || $account->admin_role_id == 2);

        $view_params = [
            "products"=>[],
            "editable"=>$editable,
        ];

        $params = $request->all();
        if(isset($params['back'])) {
            $search_params = Cache::get('products_search_params');

            $search_params['account'] = $account;

            return $this->getSearchResult($search_params);
        }

        return view("admin.pages.product.search", $view_params);
    }

    public function searchResult(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'product_id' => ['nullable', 'integer', 'min:1', 'max:32767'],
            'product_name' => ['nullable', 'string', 'max:50'],
            'product_code' => ['nullable', 'string', 'max:255'],
            'user_visible' => ['nullable', 'array', 'in:0,1'],
            'item_lineup_id' => ['nullable', 'array', 'exists:item_lineups,id'],
            'sales_target_id' => ['nullable', 'array', 'exists:sales_targets,id'],
            'sales_route_id' => ['nullable', 'array', 'exists:sales_routes,id'],
            'undelivered_summary_classification_id' => ['nullable', 'array', 'exists:undelivered_summary_classifications,id'],
            'marketing_summary_classification_id' => ['nullable', 'array', 'between:1,4'],
            'product_volume' => ['nullable', 'array', 'between:1,5']
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
        $order_detail_counts = DB::table('order_details')
            ->select('order_details.product_id', DB::raw('count(order_details.id) as count'))
            ->groupBy('order_details.product_id');
        $periodic_order_detail_counts = DB::table('periodic_order_details')
            ->select('periodic_order_details.product_id', DB::raw('count(periodic_order_details.id) as count'))
            ->groupBy('periodic_order_details.product_id');

        $products = DB::table('products')
            ->select('products.id','products.price','products.code','products.name','products.volume','products.user_visible', 'order_detail_counts.count as order_detail_count', 'periodic_order_detail_counts.count as periodic_order_detail_count')
            ->leftJoin(DB::raw(sprintf('(%s) as order_detail_counts', $order_detail_counts->toSql())), 'products.id', 'order_detail_counts.product_id')
            ->leftJoin(DB::raw(sprintf('(%s) as periodic_order_detail_counts', $periodic_order_detail_counts->toSql())), 'products.id', 'periodic_order_detail_counts.product_id');

        $search_params = $this->service->search_result($products, $params);

        $number_per_page = $search_params['number_per_page'];
        $params['number_per_page'] = $number_per_page;
        $products = $products->paginate($number_per_page, ['*'], 'page', isset($params['page']) ? $params['page'] : null);

        $account = $params['account'];
        $editable = $account !== null && ($account->admin_role_id == 1 || $account->admin_role_id == 2);

        Cache::forever('products_search_params', $params);

        return view("admin.pages.product.search", compact('products', 'search_params', 'editable'));
    }

    public function searchResultModal(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'product_name' => ['nullable', 'string', 'max:50'],
            'product_code' => ['nullable', 'string', 'max:255'],
            'item_lineup_id' => ['nullable', 'array', 'exists:item_lineups,id'],
            'marketing_summary_classification_id' => ['nullable', 'array', 'between:1,4'],
        ]);

        $responseData = new ApiResponseData($request);
        if ($validator->fails()) {
            $responseData->message = implode(" ",$validator->messages()->all());
            $responseData->saved = $validator->messages();
            $responseData->status = "error";
            return response()->json($responseData);
        }

        $products = DB::table('products')->select('id','code','name');

        $search_params = $this->service->search_result($products, $params);

        $number_per_page = $search_params['number_per_page'];
        $products = $products->paginate($number_per_page);

        $response = [
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem()
            ],
            'data' => $products
        ];

        return response()->json($response);
    }

    public function create()
    {
        return view("admin.pages.product.create");
    }

    public function createCopy($id)
    {
        $params["id"] = $id;
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:products,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return back();
        }

        $product = Product::find($id);

        $marketing_summary_classification_id = 1;
        if (!$product->is_lp && !$product->is_periodic) {
            $marketing_summary_classification_id = 1;
        }
        else if ($product->is_lp && !$product->is_periodic) {
            $marketing_summary_classification_id = 2;
        }
        else if (!$product->is_lp && $product->is_periodic) {
            $marketing_summary_classification_id = 3;
        }
        else if ($product->is_lp && $product->is_periodic) {
            $marketing_summary_classification_id = 4;
        }

        $stock_keeping_unit_id = $product->skus()->get()->pluck('stock_keeping_unit_id');
        $product_purchase_warnings_type1 = $product->purchaseWarnings()->where('purchase_warning_type_id', 1)->get()->pluck('product_id_to_warn');
        $product_purchase_warnings_type2 = $product->purchaseWarnings()->where('purchase_warning_type_id', 2)->get()->pluck('product_id_to_warn');

        unset($product->id);

        return view("admin.pages.product.create",
            compact('product', 'source', 'stock_keeping_unit_id', 'marketing_summary_classification_id', 'product_purchase_warnings_type1', 'product_purchase_warnings_type2'));
    }

    public function createSave(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'product_name' => ['required', 'string', 'max:50'],
            'product_code' => ['required', 'string', 'max:255', 'unique:products,code'],
            'item_lineup_id' => ['required', 'exists:item_lineups,id'],
            'stock_keeping_unit_id' => ['required', 'exists:stock_keeping_units,id'],
            'product_volume' => ['required','integer', 'min:0', 'max:32767'],
            'sales_target_id' => ['required', 'exists:sales_targets,id'],
            'sales_route_id' => ['required', 'exists:sales_routes,id'],
            'undelivered_summary_classification_id' => ['required', 'exists:undelivered_summary_classifications,id'],
            'marketing_summary_classification_id' => ['required', 'between:1,4'],
            'user_visible' => ['required', 'in:0,1'],
            'periodic_batch_discount_to_zero_flag' => ['required', 'in:0,1'],
            'periodic_first_nebiki' => ['nullable','integer', 'min:0', 'max:2147483647'],
            'product_price' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'product_catalog_price' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'sale_limit_at_once' => ['nullable','integer', 'min:1', 'max:32767'],
            'sale_limit_for_one_customer' => ['nullable','integer', 'min:1', 'max:32767'],
            'product_purchase_warnings_type1' => ['nullable','exists:products,id'],
            'product_purchase_warnings_type2' => ['nullable','exists:products,id'],
            'fixed_payment_method_id' => ['nullable','exists:payment_methods,id'],
            'fixed_periodic_interval_id' => ['nullable','exists:fixed_periodic_intervals,id'],
            'product_logo' => ['required', 'image', 'mimes:jpeg,bmp,png', 'max:10000']
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            $product = $this->service->createSave($params);
            $this->toastr->add('success',__("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
            return Redirect::back();
        }

        return redirect()->route('admin.product.edit',['id'=>$product->id]);
    }

    public function edit(Request $request, $id)
    {
        $params = $request->all();
        $params["id"] = $id;
        $validator = Validator::make($params, [
            'id' => ['required', 'exists:products,id'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return back();
        }

        $product = Product::find($id);

        $marketing_summary_classification_id = 1;
        if (!$product->is_lp && !$product->is_periodic) {
            $marketing_summary_classification_id = 1;
        }
        else if ($product->is_lp && !$product->is_periodic) {
            $marketing_summary_classification_id = 2;
        }
        else if (!$product->is_lp && $product->is_periodic) {
            $marketing_summary_classification_id = 3;
        }
        else if ($product->is_lp && $product->is_periodic) {
            $marketing_summary_classification_id = 4;
        }

        $stock_keeping_unit_id = $product->skus()->get()->pluck('stock_keeping_unit_id');
        $product_purchase_warnings_type1 = $product->purchaseWarnings()->where('purchase_warning_type_id', 1)->get()->pluck('product_id_to_warn');
        $product_purchase_warnings_type2 = $product->purchaseWarnings()->where('purchase_warning_type_id', 2)->get()->pluck('product_id_to_warn');

        return view("admin.pages.product.edit",
            compact('product', 'source', 'stock_keeping_unit_id', 'marketing_summary_classification_id', 'product_purchase_warnings_type1', 'product_purchase_warnings_type2'));
    }

    public function editSave(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;

        $validator = Validator::make($params, [
            'id' => ['required', 'exists:products,id'],
            'product_name' => ['required', 'string', 'max:50'],
            'product_code' => ['required', 'string', 'max:255', 'unique:products,code,'.$id],
            'item_lineup_id' => ['required', 'exists:item_lineups,id'],
            'stock_keeping_unit_id' => ['required', 'exists:stock_keeping_units,id'],
            'product_volume' => ['required','integer', 'min:0', 'max:32767'],
            'sales_target_id' => ['required', 'exists:sales_targets,id'],
            'sales_route_id' => ['required', 'exists:sales_routes,id'],
            'undelivered_summary_classification_id' => ['required', 'exists:undelivered_summary_classifications,id'],
            'marketing_summary_classification_id' => ['required', 'between:1,4'],
            'user_visible' => ['required', 'in:0,1'],
            'periodic_batch_discount_to_zero_flag' => ['required', 'in:0,1'],
            'periodic_first_nebiki' => ['nullable','integer', 'min:0', 'max:2147483647'],
            'product_price' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'product_catalog_price' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'sale_limit_at_once' => ['nullable','integer', 'min:1', 'max:32767'],
            'sale_limit_for_one_customer' => ['nullable','integer', 'min:1', 'max:32767'],
            'product_purchase_warnings_type1' => ['nullable','exists:products,id'],
            'product_purchase_warnings_type2' => ['nullable','exists:products,id'],
            'fixed_payment_method_id' => ['nullable','exists:payment_methods,id'],
            'fixed_periodic_interval_id' => ['nullable','exists:fixed_periodic_intervals,id'],
            'product_logo' => ['nullable', 'image', 'mimes:jpeg,bmp,png', 'max:10000']
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            $this->service->editSave($params);
            $this->toastr->add('success',__("common.response.success"));
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
            return Redirect::back();
        }

        $product = Product::find($id);

        return Redirect::back()->with(compact('product'));
    }

    public function delete(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'id' => ['required', 'exists:products,id'],
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

    public function download()
    {
        return view("admin.pages.product.download");
    }

    public function downloadCSV(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'type' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            return $this->service->download($params);
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
        }

        return Redirect::back();
    }
}
