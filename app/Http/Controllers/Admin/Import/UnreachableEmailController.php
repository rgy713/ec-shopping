<?php

namespace App\Http\Controllers\Admin\Import;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;

class UnreachableEmailController extends BaseController
{
    public function index()
    {
        return view("admin.pages.import.unreachable_email");
    }
}
