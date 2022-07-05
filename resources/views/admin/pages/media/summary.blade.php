@extends('admin.layouts.main.contents')

@section('title') 広告管理 > 総合分析 @endsection

@php
    //ダミーデータ
    $data=[
    0=>[
    "code"=>"9999",
    "name"=>"媒体名1"
    ],

    1=>[
    "code"=>"0001",
    "name"=>"媒体名1",
    ],

    2=>[
    "code"=>"",
    "name"=>"合計",
    ]

    ];
@endphp


@section('contents')
    <div class="row">
        <div class="col-12">
            @include("admin.components.media.summary_search_form")
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-4 offset-4">
            <button class="btn btn-sm btn-primary btn-block"><i class="fa fa-search"></i>&nbsp;検索</button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @include("admin.components.media.summary_result")
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @include("admin.components.media.periodic_summary_result")
        </div>
    </div>
@endsection

