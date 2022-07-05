{{-- 左メニュー制御ボタン --}}
<button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
    <span class="navbar-toggler-icon"></span>
</button>

{{-- ロゴ --}}
<a class="navbar-brand" href="#">
    <img class="navbar-brand-full" src="/images/app/logo1.png" width="89" height="25" alt="">
    <img class="navbar-brand-minimized" src="/images/app/logo2.png" width="30" height="30" alt="">
</a>

{{-- 左メニュー制御ボタン --}}
<button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
    <span class="navbar-toggler-icon"></span>
</button>

{{-- ヘッダエリアのショートカット --}}
<ul class="nav navbar-nav d-md-down-none">
    <li class="nav-item px-3">
        <a class="nav-link" href="#">ショートカット１</a>
    </li>
    <li class="nav-item px-3">
        <a class="nav-link" href="#">ショートカット２</a>
    </li>
    <li class="nav-item px-3">
        <a class="nav-link" href="#">ショートカット3</a>
    </li>
</ul>

{{-- ヘッダ右エリア --}}
<ul class="nav navbar-nav ml-auto">
    {{$admin->name}} / {{$admin->email}}
</ul>

{{-- 右メニューボタン --}}
<button class="navbar-toggler aside-menu-toggler d-md-down-none" type="button" data-toggle="aside-menu-lg-show">
    <span class="navbar-toggler-icon"></span>
</button>
<button class="navbar-toggler aside-menu-toggler d-lg-none" type="button" data-toggle="aside-menu-show">
    <span class="navbar-toggler-icon"></span>
</button>

