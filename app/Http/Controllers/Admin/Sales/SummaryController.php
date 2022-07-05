<?php

namespace App\Http\Controllers\Admin\Sales;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use App\Services\SummaryService;
use App\Services\FlashToastrMessageService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class SummaryController extends BaseController
{
    private $service;
    private $toastr;

    public function __construct()
    {
        $this->service = app(SummaryService::class);
        $this->toastr = app(FlashToastrMessageService::class);
    }

    public function index()
    {
        return view("admin.pages.sales.index");
    }

    /**
     * 会計部門集計
     */
    public function accounting()
    {
        return view("admin.pages.sales.accounting");
    }

    public function accountingSummary(Request $request)
    {
        $params = $request->all();
        $result = $this->getTerm($params);

        if (!is_null($result['validator'])) {
            $validator = $result['validator'];
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $term_from = $result['term']['term_from'];
        $term_to = $result['term']['term_to'];

        $summary1 = $this->service->accountingSummary(1, $term_from, $term_to);
        $summary2 = $this->service->accountingSummary(2, $term_from, $term_to);
        $summary3 = $this->service->accountingSummary(3, $term_from, $term_to);
        $summary4 = $this->service->accountingSummary(4, $term_from, $term_to);
        $summary5 = $this->service->accountingSummary(5, $term_from, $term_to);

        $view_params = [
            'summary1'=>$summary1,
            'summary2'=>$summary2,
            'summary3'=>$summary3,
            'summary4'=>$summary4,
            'summary5'=>$summary5,
            'term_from'=>$term_from,
            'term_to'=>$term_to,
        ];

        if (isset($params['summary_term_year'])) {
            $view_params['summary_term_year'] = $params['summary_term_year'];
        }
        if (isset($params['summary_term_month'])) {
            $view_params['summary_term_month'] = $params['summary_term_month'];
        }
        if (isset($params['summary_term_from'])) {
            $view_params['summary_term_from'] = $params['summary_term_from'];
        }
        if (isset($params['summary_term_to'])) {
            $view_params['summary_term_to'] = $params['summary_term_to'];
        }

        return view("admin.pages.sales.accounting",  $view_params);
    }

    public function accountingDownloadCSV(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'term_from' => ['nullable', 'date'],
            'term_to' => ['nullable', 'date'],
            'accounting_type' => ['required', 'between:1,5'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $term_from = $params['term_from'];
        $term_to = $params['term_to'];
        $accounting_type = $params['accounting_type'];

        return $this->service->accountingDownloadCSV($accounting_type, $term_from, $term_to);
    }

    public function marketing(){
        return view("admin.pages.sales.marketing");
    }

    public function marketingSummary(Request $request)
    {
        $params = $request->all();
        $result = $this->getTerm($params);

        if (!is_null($result['validator'])) {
            $validator = $result['validator'];
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $term_from = $result['term']['term_from'];
        $term_to = $result['term']['term_to'];

        $summary1 = $this->service->marketingSummary(1, 1, $term_from, $term_to);
        $summary2 = $this->service->marketingSummary(2, 1, $term_from, $term_to);
        $summary3 = $this->service->marketingSummary(3, 1, $term_from, $term_to);
        $summary4 = $this->service->marketingSummary(4, 1, $term_from, $term_to);
        $summary5 = $this->service->marketingSummary(5, 1, $term_from, $term_to);

        $view_params = [
            'summary1'=>$summary1,
            'summary2'=>$summary2,
            'summary3'=>$summary3,
            'summary4'=>$summary4,
            'summary5'=>$summary5,
            'term_from'=>$term_from,
            'term_to'=>$term_to,
        ];

        if (isset($params['summary_term_year'])) {
            $view_params['summary_term_year'] = $params['summary_term_year'];
        }
        if (isset($params['summary_term_month'])) {
            $view_params['summary_term_month'] = $params['summary_term_month'];
        }
        if (isset($params['summary_term_from'])) {
            $view_params['summary_term_from'] = $params['summary_term_from'];
        }
        if (isset($params['summary_term_to'])) {
            $view_params['summary_term_to'] = $params['summary_term_to'];
        }

        return view("admin.pages.sales.marketing",  $view_params);
    }

    public function marketingDownloadCSV(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'term_from' => ['nullable', 'date'],
            'term_to' => ['nullable', 'date'],
            'marketing_type' => ['required', 'between:1,5'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $term_from = $params['term_from'];
        $term_to = $params['term_to'];
        $marketing_type = $params['marketing_type'];

        return $this->service->marketingDownloadCSV($marketing_type, $term_from, $term_to);
    }

    public function periodicCount()
    {
        $summary1 = $this->service->periodicCountSummary(0);
        $summary2 = $this->service->periodicCountSummary(1);
        $summary3 = $this->service->periodicCountSummary(3);
        $summary4 = $this->service->periodicCountSummary(4);
        $summary5 = $this->service->periodicCountSummary(5);

        return view("admin.pages.sales.periodic_count",
            ['summary1'=>$summary1, 'summary2'=>$summary2, 'summary3'=>$summary3, 'summary4'=>$summary4, 'summary5'=>$summary5]);
    }

    public function periodicCountDownloadCsv(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'export_type' => ['required', 'in:0,1'],
            'summary_term_from' => ['required', 'date'],
            'summary_term_to' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $result = $this->getTerm($params);

        if (!is_null($result['validator'])) {
            $validator = $result['validator'];
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $term_from = $result['term']['term_from'];
        $term_to = $result['term']['term_to'];
        $export_type = $params['export_type'];

        return $this->service->periodicCountDownloadCsv($export_type, $term_from, $term_to);
    }

    public function wholesale(){
        return view("admin.pages.sales.wholesale");
    }

    public function wholesaleSummary(Request $request){
        $params = $request->all();
        $result = $this->getTerm($params);

        if (!is_null($result['validator'])) {
            $validator = $result['validator'];
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $term_from = $result['term']['term_from'];
        $term_to = $result['term']['term_to'];

        $summary1 = $this->service->wholesaleSummary1($term_from, $term_to);
        $summary2 = $this->service->wholesaleSummary2($term_from, $term_to);

        $view_params = [
            'summary1'=>$summary1,
            'summary2'=>$summary2,
            'term_from'=>$term_from,
            'term_to'=>$term_to,
        ];

        if (isset($params['summary_term_year'])) {
            $view_params['summary_term_year'] = $params['summary_term_year'];
        }
        if (isset($params['summary_term_month'])) {
            $view_params['summary_term_month'] = $params['summary_term_month'];
        }
        if (isset($params['summary_term_from'])) {
            $view_params['summary_term_from'] = $params['summary_term_from'];
        }
        if (isset($params['summary_term_to'])) {
            $view_params['summary_term_to'] = $params['summary_term_to'];
        }

        return view("admin.pages.sales.wholesale", $view_params);
    }

    public function wholesaleDownloadCSV(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'term_from' => ['nullable', 'date'],
            'term_to' => ['nullable', 'date'],
            'customer_id' => ['required', 'between:0,300'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $term_from = $params['term_from'];
        $term_to = $params['term_to'];
        $customer_id = $params['customer_id'];

        return $this->service->wholesaleDownloadCSV($customer_id, $term_from, $term_to);
    }

    public function age(){
        return view("admin.pages.sales.age");
    }

    public function ageSummary(Request $request)
    {
        $params = $request->all();
        $result = $this->getTerm($params);

        if (!is_null($result['validator'])) {
            $validator = $result['validator'];
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $term_from = $result['term']['term_from'];
        $term_to = $result['term']['term_to'];

        $summary1 = $this->service->ageSummary($term_from, $term_to);

        $view_params = [
            'summary1'=>$summary1,
            'term_from'=>$term_from,
            'term_to'=>$term_to,
        ];

        if (isset($params['summary_term_year'])) {
            $view_params['summary_term_year'] = $params['summary_term_year'];
        }
        if (isset($params['summary_term_month'])) {
            $view_params['summary_term_month'] = $params['summary_term_month'];
        }
        if (isset($params['summary_term_from'])) {
            $view_params['summary_term_from'] = $params['summary_term_from'];
        }
        if (isset($params['summary_term_to'])) {
            $view_params['summary_term_to'] = $params['summary_term_to'];
        }

        return view("admin.pages.sales.age", $view_params);
    }

    public function ageDownloadCSV(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'term_from' => ['nullable', 'date'],
            'term_to' => ['nullable', 'date'],
            'age' => ['required', 'between:0,300'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $term_from = $params['term_from'];
        $term_to = $params['term_to'];
        $age = $params['age'];

        return $this->service->ageDownloadCSV($age, $term_from, $term_to);
    }

    public function payment()
    {
        return view("admin.pages.sales.payment");
    }

    public function paymentSummary(Request $request)
    {
        $params = $request->all();
        $result = $this->getTerm($params);

        if (!is_null($result['validator'])) {
            $validator = $result['validator'];
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $term_from = $result['term']['term_from'];
        $term_to = $result['term']['term_to'];

        $summary1 = $this->service->paymentSummary($term_from, $term_to);

        $view_params = [
            'summary1'=>$summary1,
            'term_from'=>$term_from,
            'term_to'=>$term_to,
        ];

        if (isset($params['summary_term_year'])) {
            $view_params['summary_term_year'] = $params['summary_term_year'];
        }
        if (isset($params['summary_term_month'])) {
            $view_params['summary_term_month'] = $params['summary_term_month'];
        }
        if (isset($params['summary_term_from'])) {
            $view_params['summary_term_from'] = $params['summary_term_from'];
        }
        if (isset($params['summary_term_to'])) {
            $view_params['summary_term_to'] = $params['summary_term_to'];
        }

        return view("admin.pages.sales.payment", $view_params);
    }

    public function paymentDownloadCSV(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'term_from' => ['nullable', 'date'],
            'term_to' => ['nullable', 'date'],
            'payment_method_id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            $this->toastr->add('error', implode(" ",$validator->messages()->all()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $term_from = $params['term_from'];
        $term_to = $params['term_to'];
        $payment_method_id = $params['payment_method_id'];

        return $this->service->paymentDownloadCSV($payment_method_id, $term_from, $term_to);
    }

    public function getTerm($params) {
        $validator = Validator::make($params, [
            'term' => ['required', Rule::in(['month', 'term'])],
        ]);

        if ($validator->fails()) {
            return [
                "validator"=>$validator,
                "term"=>null
            ];
        }

        $term = $params['term'];
        if (!strcmp($term, 'month')) {
            $validator = Validator::make($params, [
                'summary_term_year' => ['nullable', 'required_with:summary_term_month', 'integer', 'min: 2000', 'max: 2999'],
                'summary_term_month' => ['nullable', 'required_with:summary_term_year', 'integer', 'between:1,12'],
            ]);
        }
        else if (!strcmp($term, 'term')) {
            $validator = Validator::make($params, [
                'summary_term_from' => ['nullable', 'required_with:summary_term_to', 'date'],
                'summary_term_to' => 'nullable|required_with:summary_term_from|date'. (isset($params['summary_term_from']) ? '|after_or_equal:summary_term_from' : ''),
            ]);
        }

        if ($validator->fails()) {
            return [
                "validator"=>$validator,
                "term"=>null
            ];
        }

        $term_from = null;
        $term_to = null;
        if (!strcmp($term, 'month') && isset($params['summary_term_year']) && isset($params['summary_term_month'])) {
            $summary_term_year = $params['summary_term_year'];
            $summary_term_month = $params['summary_term_month'];

            $term_from = Carbon::createFromDate($summary_term_year, $summary_term_month, 1)->startOfDay();;
            $term_to = Carbon::createFromDate($summary_term_year, $summary_term_month, 1)->startOfDay()->addMonth(1);
        }
        else if (!strcmp($term, 'term') && isset($params['summary_term_from']) && isset($params['summary_term_to'])) {
            $summary_term_from = $params['summary_term_from'];
            $summary_term_to = $params['summary_term_to'];

            $term_from = Carbon::parse($summary_term_from)->format('Y-M-d H:m:s');
            $term_to = Carbon::parse($summary_term_to)->format('Y-M-d H:m:s');
        }

        return [
            "validator"=>null,
            "term"=>[
                "term_from"=>$term_from,
                "term_to"=>$term_to,
            ]
        ];
    }
}
