@extends('admin.layouts.main.contents')

@section('title') 広告管理 > タグ詳細 @endsection

@section('contents')
    <div class="row">
        {{-- ページ一覧 start --}}
        <div class="col-12">
            @include('admin.components.media.tag_edit_form')
        </div>

        <div class="col-12">
            @include('admin.components.media.pages_list',['buttonEnable'=>true,'checkboxEnable'=>true,'linkEnable'=>false])
        </div>

        <div class="col-12">
            @include("admin.components.common.edit_form_button")
        </div>

    </div>
@endsection

