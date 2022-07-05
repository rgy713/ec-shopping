<?php

namespace App\Http\Controllers\Admin\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SummaryController extends Controller
{
    public function index()
    {
        return view("admin.pages.media.summary");
    }
}
