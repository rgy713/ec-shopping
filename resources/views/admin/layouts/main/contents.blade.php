{{-- メインエリアレイアウト:管理画面各ページはこのテンプレートを継承し、contents セクションを実装する。--}}
@extends('admin.layouts.main.sections')

@push('content_js')
    {{-- メインエリアレイアウト共通のjs処理--}}
    <script>
        $(function () {
            {{-- フォームリセットの割り当て--}}
            $(".form-reset-button").on('click',function(){
                alert("共通ボタンとして実装可能か要検討");
                if(confirm("フォーム入寮内容をリセットします。よろしいですか？")){
                    app.functions.resetForm('form');
                }
            });
        });
    </script>
    {{-- 各ページのjavascriptを出力 --}}
@endpush

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
