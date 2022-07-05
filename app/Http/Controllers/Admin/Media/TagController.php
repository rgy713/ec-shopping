<?php

namespace App\Http\Controllers\Admin\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;

class TagController extends BaseController
{
    public function index()
    {
        return view("admin.pages.media.tag_list");
    }

    public function edit()
    {
        return view("admin.pages.media.tag_edit");
    }

    public function create()
    {
        return view("admin.pages.media.tag_edit");
    }

    public function page()
    {
        return view("admin.pages.media.page_info");
    }

}
