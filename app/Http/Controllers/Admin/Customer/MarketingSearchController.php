<?php

namespace App\Http\Controllers\Admin\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Services\FlashToastrMessageService;
use App\Services\CustomerMarketingSearchService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Cache;

class MarketingSearchController extends BaseController
{
    private $service;
    private $toastr;

    /**
     * AccountController constructor.
     */
    public function __construct()
    {
        $this->service = app(CustomerMarketingSearchService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    public function index(Request $request)
    {
        $params = $request->all();
        if(isset($params['back'])){
            $search_params = Cache::get('search_params');
            $customers = $this->service->search($search_params);
            return view("admin.pages.customer.marketing_search", compact('customers', 'search_params'));
        }
        else
            return view("admin.pages.customer.marketing_search");
    }

    protected function makeValidator($params)
    {
        return Validator::make($params, [
            'customer_id_min'=>['nullable', 'integer', 'min:1', 'max:2147483647'],
            'customer_id_max'=>['nullable', 'integer', 'min:1', 'max:2147483647',(isset($params['customer_id_min']) ?'greater_than_field:customer_id_min':'')],
            'customer_status'=>['nullable', 'array'],
            'customer_status.*'=>['nullable', 'exists:customer_statuses,id'],
            'customer_mail_flag'=>['nullable', 'array'],
            'customer_mail_flag.*'=>['nullable', 'integer', 'in:2,3'],
            'customer_dm_flag'=>['nullable', 'array'],
            'customer_dm_flag.*'=>['nullable', 'integer', 'in:1,2'],
            'customer_created_at_from'=>['nullable', 'string', 'date_format:Y-m-d'],
            'customer_created_at_to'=>['nullable', 'string', 'date_format:Y-m-d',(isset($params['customer_created_at_from']) ? 'after_or_equal:customer_created_at_from':'')],
            'customer_updated_at_from'=>['nullable', 'string', 'date_format:Y-m-d'],
            'customer_updated_at_to'=>['nullable', 'string', 'date_format:Y-m-d',(isset($params['customer_updated_at_from']) ? 'after_or_equal:customer_updated_at_from':'')],
            'customer_prefecture'=>['nullable', 'array'],
            'customer_prefecture.*'=>['nullable', 'exists:prefectures,id'],
            'customer_birthday_from'=>['nullable', 'string', 'date_format:Y-m-d'],
            'customer_birthday_to'=>['nullable', 'string', 'date_format:Y-m-d',(isset($params['customer_birthday_from']) ? 'after_or_equal:customer_birthday_from':'')],
            'customer_birth_month'=>['nullable', 'array'],
            'customer_birth_month.*'=>['nullable', 'integer', 'min:1', 'max:12'],
            'customer_pfm'=>['nullable', 'array'],
            'customer_pfm.*'=>['nullable', 'exists:pfm_statuses,id'],
            'customer_media_code'=>['nullable', 'array'],
            'customer_media_code.*'=>['nullable', 'exists:advertising_media,code'],
            'customer_item_lineup'=>['nullable', 'array'],
            'customer_item_lineup.*'=>['nullable', 'exists:item_lineups,id'],
            'customer_sales_target'=>['nullable', 'array'],
            'customer_sales_target.*'=>['nullable', 'exists:sales_targets,id'],
            'customer_sales_route'=>['nullable', 'array'],
            'customer_sales_route.*'=>['nullable', 'exists:sales_routes,id'],
            'customer_undelivered_summary_classification'=>['nullable', 'array'],
            'customer_undelivered_summary_classification.*'=>['nullable', 'integer', 'in:1,2,3'],
            'customer_marketing_summary_classification'=>['nullable', 'array'],
            'customer_marketing_summary_classification.*'=>['nullable', 'integer', 'in:1,2,3,4'],
            'customer_product_volume_list'=>['nullable', 'array'],
            'customer_product_volume_list.*'=>['nullable', 'integer', 'in:1,2,3,4,5'],
            'customer_buy_total_min'=>['nullable', 'numeric', 'min:0', 'max:2147483647'],
            'customer_buy_total_max'=>['nullable', 'numeric', 'min:0', 'max:2147483647',(isset($params['customer_buy_total_min']) ?'greater_than_field:customer_buy_total_min':'')],
            'customer_buy_times_min'=>['nullable', 'integer', 'min:0', 'max:32767'],
            'customer_buy_times_max'=>['nullable', 'integer', 'min:0', 'max:32767',(isset($params['customer_buy_times_min']) ?'greater_than_field:customer_buy_times_min':'')],
            'customer_buy_volume_min'=>['nullable', 'integer', 'min:0', 'max:32767'],
            'customer_buy_volume_max'=>['nullable', 'integer', 'min:0', 'max:32767',(isset($params['customer_buy_volume_min']) ?'greater_than_field:customer_buy_volume_min':'')],
            'customer_last_buy_date_from'=>['nullable', 'string', 'date_format:Y-m-d'],
            'customer_last_buy_date_to'=>['nullable', 'string', 'date_format:Y-m-d',(isset($params['customer_last_buy_date_from']) ? 'after_or_equal:customer_last_buy_date_from':'')],
            'customer_withdrawal_date_from'=>['nullable', 'string', 'date_format:Y-m-d'],
            'customer_withdrawal_date_to'=>['nullable', 'string', 'date_format:Y-m-d',(isset($params['customer_withdrawal_date_from']) ? 'after_or_equal:customer_withdrawal_date_from':'')],
            'customer_product_lineup'=>['nullable', 'array'],
            'customer_product_lineup.*'=>['nullable', 'exists:item_lineups,id'],
            'customer_periodic_stop_flag'=>['nullable', 'array'],
            'customer_periodic_stop_flag.*'=>['nullable', 'integer', 'in:0,1'],
            'customer_periodic_stop_date_from'=>['nullable', 'string', 'date_format:Y-m-d'],
            'customer_periodic_stop_date_to'=>['nullable', 'string', 'date_format:Y-m-d',(isset($params['customer_periodic_stop_date_from']) ? 'after_or_equal:customer_periodic_stop_date_from':'')],
            'sample1'=>['nullable', 'integer', 'in:0,1,2'],
            'customer_item_lineup_sample1'=>['nullable', 'array'],
            'customer_item_lineup_sample1.*'=>['nullable', 'exists:item_lineups,id'],
            'sampleA'=>['nullable', 'integer', 'in:0,1,2,3'],
            'customer_item_lineup_sampleA'=>['nullable', 'array'],
            'customer_item_lineup_sampleA.*'=>['nullable', 'exists:item_lineups,id'],
            'sampleB'=>['nullable', 'integer', 'in:0,1,2,3'],
            'customer_item_lineup_sampleB'=>['nullable', 'array'],
            'customer_item_lineup_sampleB.*'=>['nullable', 'exists:item_lineups,id'],
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

        $customers = $this->service->search($params);
        $search_params = $params;

        Cache::forever('return_route_name', 'admin.customer.marketing_search');
        Cache::forever('search_params', $search_params);

        return view("admin.pages.customer.marketing_search", compact('customers', 'search_params'));
    }

    public function downloadCsv(Request $request)
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
