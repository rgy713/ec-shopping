<?php

namespace App\Http\Controllers\Admin\Periodic;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;

class PeriodicController extends BaseController
{
    public function create()
    {
        //TODO:リファクタリング
        $param=[
            'mode'=>'create',
            "isPeriodic"=>true
        ];

        return view("admin.pages.periodic.create",$param);
    }

    public function edit()
    {
        //TODO:リファクタリング
        $param=[
            'mode'=>'edit',
            "isPeriodic"=>true
        ];
        return view("admin.pages.periodic.edit",$param);
    }

    public function popupCreate()
    {
        //TODO:リファクタリング
        $param=[
            'mode'=>'create',
            "isPeriodic"=>true
        ];
        return view("admin.pages.periodic.popup_create",$param);
    }

}
