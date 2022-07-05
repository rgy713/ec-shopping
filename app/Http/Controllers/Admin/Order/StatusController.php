<?php

namespace App\Http\Controllers\Admin\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;

class StatusController extends BaseController
{
    public function index()
    {
        return view("admin.pages.order.status");
    }
}
