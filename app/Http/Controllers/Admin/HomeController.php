<?php

namespace App\Http\Controllers\Admin;

use App\Services\AdminSettingService;
use App\Services\SystemLogService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends BaseController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service = app()->makeWith(AdminSettingService::class, ["model" => Auth::user()]);
//        $service = app()->make(AdminSettingService::class);
//        dd($service->getModel());

        return view('admin.home')->with([
                "test" => new Carbon()
            ]
        );
    }
}
