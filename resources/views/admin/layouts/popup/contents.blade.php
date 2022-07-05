{{-- メインエリアレイアウト:管理画面各ページはこのテンプレートを継承し、contents セクションを実装する。--}}
@extends('admin.layouts.popup.sections')

@section('main')
    {{-- パンくずエリア --}}
    <ol class="breadcrumb">
        <li class="breadcrumb-item">@yield('title')</li>

        <li class="breadcrumb-menu d-md-down-none">
            <div class="btn-group" role="group" aria-label="Button group">
                <a class="btn form-reset-button" href="#">
                    <i class="fa fa-remove"></i> &nbsp;フォームのリセット</a>
                <a class="btn" href="#">
                    <i class="{{__("admin.icon_class.setting")}}"></i> &nbsp;操作2</a>
            </div>
        </li>
    </ol>

    <div class="container-fluid">
        <div id="ui-view">
            @yield('contents')
        </div>
    </div>

@endsection
