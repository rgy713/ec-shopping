@extends('admin.layouts.main.contents')

@section('title') 広告管理 > 設置対象ページ情報 @endsection

@section('contents')
    <div class="row">
        {{-- ページ一覧 start --}}
        <div class="col-12">
            @include('admin.components.media.page_basic_info')
        </div>

        <div class="col-12">
            @include('admin.components.media.page_info_head_tags')
        </div>

        <div class="col-12">
            @include('admin.components.media.page_info_body_tags')
        </div>

    </div>
@endsection

