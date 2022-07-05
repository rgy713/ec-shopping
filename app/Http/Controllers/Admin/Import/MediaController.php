<?php

namespace App\Http\Controllers\Admin\Import;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;

class MediaController extends BaseController
{
    public function index()
    {
        return view("admin.pages.import.media");
    }

    public function confirm()
    {
        return view("admin.pages.import.media_confirm");
    }

}
