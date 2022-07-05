<?php

namespace App\Http\Controllers\Admin\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use App\Services\FlashToastrMessageService;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class DetailedSearchController extends BaseController
{
    private $service;
    private $toastr;

    /**
     * AccountController constructor.
     */
    public function __construct()
    {
        $this->service = app(CustomerService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    public function index(Request $request)
    {
        $params = $request->all();
        if(isset($params['back'])){
            $search_params = Cache::get('search_params');
            $customers = $this->service->detailSearch($search_params);
            return view("admin.pages.customer.detailed_search", compact('customers', 'search_params'));
        }
        else
            return view("admin.pages.customer.detailed_search");
    }

    protected function makeValidator($params)
    {
        return Validator::make($params, [
            'customer_id'=>['nullable', 'integer', 'min:0', 'max:2147483647'],
            'customer_birthday_from'=>['nullable', 'string', 'date_format:Y-m-d'],
            'customer_birthday_to'=>['nullable', 'string', 'date_format:Y-m-d', (isset($params['customer_birthday_from']) ? 'after_or_equal:customer_birthday_from':'')],
            'customer_last_buy_date_from'=>['nullable', 'string', 'date_format:Y-m-d'],
            'customer_last_buy_date_to'=>['nullable', 'string', 'date_format:Y-m-d', (isset($params['customer_last_buy_date_from']) ? 'after_or_equal:customer_last_buy_date_from':'')],
            'customer_buy_total_min'=>['nullable', 'numeric', 'min:0', 'max:2147483647'],
            'customer_buy_total_max'=>['nullable', 'numeric', 'min:0', 'max:2147483647', (isset($params['customer_buy_total_min']) ?'greater_than_field:customer_buy_total_min':'')],
            'customer_status'=>['nullable', 'array'],
            'customer_status.*'=>['nullable', 'exists:customer_statuses,id'],
            'customer_periodic_id_min'=>['nullable', 'integer', 'min:0', 'max:2147483647'],
            'customer_periodic_id_max'=>['nullable', 'integer', 'min:0', 'max:2147483647', (isset($params['customer_periodic_id_min']) ?'greater_than_field:customer_periodic_id_min':'')],
            'customer_periodic_count_min'=>['nullable', 'integer', 'min:0', 'max:32767'],
            'customer_periodic_count_max'=>['nullable', 'integer', 'min:0', 'max:32767', (isset($params['customer_periodic_count_max']) ?'greater_than_field:customer_periodic_count_max':'')],
            'customer_periodic_status_flag'=>['nullable', 'integer', 'in:0,1'],
            'customer_periodic_stop_flag'=>['nullable', 'integer', 'in:0,1'],
            'customer_product_lineup'=>['nullable', 'array'],
            'customer_product_lineup.*'=>['nullable', 'exists:item_lineups,id'],
            'customer_payment_method'=>['nullable', 'array'],
            'customer_payment_method.*'=>['nullable', 'exists:payment_methods,id'],
            'customer_periodic_prev_create_from'=>['nullable', 'string', 'date_format:Y-m-d'],
            'customer_periodic_prev_create_to'=>['nullable', 'string', 'date_format:Y-m-d', (isset($params['customer_periodic_prev_create_from']) ? 'after_or_equal:customer_periodic_prev_create_from':'')],
            'customer_periodic_next_create_from'=>['nullable', 'string', 'date_format:Y-m-d'],
            'customer_periodic_next_create_to'=>['nullable', 'string', 'date_format:Y-m-d', (isset($params['customer_periodic_next_create_from']) ? 'after_or_equal:customer_periodic_next_create_from':'')],
        ]);
    }

    public function search(Request $request)
    {
        $params = $request->all();

        $validator = $this->makeValidator($params);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try {
            $customers = $this->service->detailSearch($params);
        } catch (\Exception $e) {
            $this->toastr->add('error', $e->getMessage());
            return Redirect::back()->withInput();
        }

        $search_params = $params;

        Cache::forever('return_route_name', 'admin.customer.detailed_search');
        Cache::forever('search_params', $search_params);

        return view("admin.pages.customer.detailed_search", compact('customers', 'search_params'));
    }

    public function download(Request $request)
    {
        $params = $request->all();

        $validator = $this->makeValidator($params);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        return $this->service->downloadCsv($params);
    }
}
