<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Services\CustomerService;
use App\Services\FlashToastrMessageService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Redirect;


class BasicSearchController extends BaseController
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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $params = $request->all();
        if(isset($params['back'])){
            $search_params = Cache::get('search_params');
            $customers = $this->service->search($search_params);
            return view("admin.pages.customer.basic_search", compact('customers', 'search_params'));
        }
        else
            return view("admin.pages.customer.basic_search");
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $params = $request->all();
        $customers = $this->service->search($params);
        $search_params = $params;

        Cache::forever('return_route_name', 'admin.customer.basic_search');
        Cache::forever('search_params', $search_params);

        return view("admin.pages.customer.basic_search", compact('customers', 'search_params'));
    }
}
