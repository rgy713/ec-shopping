@extends('admin.layouts.main.contents')

@section('title') 広告管理 > タグ管理 @endsection

@section('contents')
    <div class="row">
        {{-- タグ一覧 start --}}
        <div class="col-12">
            @include('admin.components.media.tags_list')
        </div>

        <div class="col-12">
            @include('admin.components.media.pages_list',['buttonEnable'=>false,'checkboxEnable'=>false,'linkEnable'=>true])
        </div>

    </div>
@endsection

