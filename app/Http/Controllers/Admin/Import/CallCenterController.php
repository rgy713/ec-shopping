<?php

namespace App\Http\Controllers\Admin\Import;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;

class CallCenterController extends BaseController
{
    public function index()
    {
        return view("admin.pages.import.call_center");
    }
}
