@inject('FlashToastrMessageService', 'App\Services\FlashToastrMessageService')
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="">
    <meta name="keyword" content="">

    {{-- 全管理画面で共通のスタイル --}}
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/coreui.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vue.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    {{-- 各ページに固有のスタイル --}}
    @stack('content_css')
    {{--@yield('content_css')--}}
</head>

{{-- ログイン者のメニューデフォルト表示設定を反映 --}}
<body class="app header-fixed sidebar-fixed aside-menu-fixed @if($admin->option_left_menu) sidebar-lg-show @endif @if($admin->option_right_menu) aside-menu-show @endif">

{{-- ヘッダ --}}
<header class="app-header navbar">
    @yield('header')
</header>

{{-- コンテンツ --}}
<div class="app-body">
    {{-- 左メニュー --}}
    <div class="sidebar">
        @yield('left-menu')
    </div>

    {{-- メインコンテンツ --}}
    <main class="main">
        @yield('main')
    </main>

    {{-- 右メニュー --}}
    <aside class="aside-menu">
        @yield('right-menu')
    </aside>
</div>

{{-- フッタ --}}
<footer class="app-footer">
    @yield('footer')
</footer>

{{-- javascriptにサーバーからAPIトークンを渡す --}}
<script>
    window.Laravel = {!! json_encode(['apiToken' => $admin->api_token ?? null]) !!};
</script>
{{-- 全管理画面で共通のjavascriptファイルをロードする --}}
{{--<script src="{{ asset('js/bootstrap.js') }}"></script>--}}
<script src="{{ asset('js/coreui.js') }}"></script>
<script src="{{ asset('js/vue.js') }}"></script>
<script src="{{ asset('js/zipcode.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

{{-- Todo:webpack管理に移動したいが、開発中はココのほうが都合が良い--}}
<script>
    $(function () {
        //入力フォーム、btn-primary,btn-secondaryクラスのbutton以外のtabindexを削除
        $("a").attr("tabindex","-1");
        $("button:not(.btn-primary,.btn-secondary,.btn-info,.btn-warning,.btn-danger)").attr("tabindex","-1");
        $("input[readonly]").attr("tabindex","-1");

        //テーブルヘッダ中央寄せ
        // $("th").addClass("text-center");
        $("th").addClass("table-secondary");

        //number typeの内容を右寄せ
        $("input[type=number]").addClass("text-right");


        @if(app('request')->input('spec')==="off")
        $(".popover").remove();
        @endif

        {{-- FlashToastrMessageService経由でsessionからtoastrメッセージを取得して表示。pull()した時点でsessionから消える --}}
        @foreach($FlashToastrMessageService->pull() as $systemMessage)
            @if (!empty($systemMessage->message))
                app.functions.displayToastr('{{$systemMessage->level}}','{{$systemMessage->message}}');
            @endif
        @endforeach


    });
</script>

{{-- 各ページに固有のjs --}}
{{--@yield('content_js')--}}
@stack('content_js')
</body>
</html>


