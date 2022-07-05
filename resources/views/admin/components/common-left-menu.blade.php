<nav class="sidebar-nav">
    <ul class="nav">

        <li class="nav-divider"></li>
        <li class="nav-title">Admin</li>
        {{-- 顧客 --}}
        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon icon-user text-info"></i> 顧客管理</a>
            <ul class="nav-dropdown-items">
                <li class="nav-item"><a class="nav-link" href="{{route("admin.customer.basic_search")}}"><i class="nav-icon fa fa-circle"></i> 簡易検索(1-1)</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.customer.detailed_search")}}"><i class="nav-icon fa fa-circle"></i> 詳細検索(1-2)</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.customer.create")}}"><i class="nav-icon fa fa-circle"></i> 顧客登録(1-4)</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.customer.marketing_search")}}"><i class="nav-icon fa fa-circle"></i> マーケティング(1-5)</a></li>
            </ul>
        </li>

        {{-- 受注 --}}
        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon icon-basket text-info"></i> 受注管理</a>
            <ul class="nav-dropdown-items">
                <li class="nav-item"><a class="nav-link" href="{{route("admin.order.search")}}"><i class="nav-icon fa fa-circle"></i> 受注検索(***)</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.order.create")}}"><i class="nav-icon fa fa-circle"></i> 受注登録(2-1)</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.periodic.search")}}"><i class="nav-icon fa fa-circle"></i> 定期検索(***)</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.periodic.create")}}"><i class="nav-icon fa fa-circle"></i> 定期登録(2-2)</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.order.shipping")}}"><i class="nav-icon fa fa-circle"></i> 配送管理(2-3)</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.order.utility")}}"><i class="nav-icon fa fa-circle"></i> 受注管理ツール(2-6)</a></li>
            </ul>
        </li>

        {{-- 商品 --}}
        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon icon-bag text-info"></i> 商品管理</a>
            <ul class="nav-dropdown-items">
                <li class="nav-item"><a class="nav-link" href="{{route("admin.product.search")}}"><i class="nav-icon fa fa-circle"></i> 商品検索(3-1)</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.product.create")}}"><i class="nav-icon fa fa-circle"></i> 商品登録(3-2)</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.product.download")}}"><i class="nav-icon fa fa-circle"></i> {{__("admin.operation.csv_download")}}</a></li>
            </ul>
        </li>

        {{-- 広告 --}}
        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon icon-link text-info"></i> 広告管理</a>
            <ul class="nav-dropdown-items">
                <li class="nav-item"><a class="nav-link" href="{{route("admin.media.search")}}"><i class="nav-icon fa fa-circle"></i> 検索(※4-1)</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.media.create")}}"><i class="nav-icon fa fa-circle"></i> 登録(※4-1)</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.media.asp")}}"><i class="nav-icon fa fa-circle"></i> ASP一覧(4-2)</a></li>
                {{--<li class="nav-item"><a class="nav-link" href="{{route("admin.media.import")}}"><i class="nav-icon fa fa-circle"></i> CSVインポート(4-3)</a></li>--}}
                <li class="nav-item"><a class="nav-link" href="{{route("admin.media.summary")}}"><i class="nav-icon fa fa-circle"></i> 総合分析(4-5)</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.media.tag.list")}}"><i class="nav-icon fa fa-circle"></i> タグ管理(※)</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.media.tag.create")}}"><i class="nav-icon fa fa-circle"></i> タグ作成(※)</a></li>
            </ul>
        </li>

        {{-- 売上 --}}
        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon icon-chart text-info"></i> 売上管理</a>
            <ul class="nav-dropdown-items">
                <li class="nav-item"><a class="nav-link" href="{{route("admin.sales.summary.accounting")}}"><i class="nav-icon fa fa-circle"></i> 会計部門集計</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.sales.summary.marketing")}}"><i class="nav-icon fa fa-circle"></i> マーケ部門集計</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.sales.summary.periodic_count")}}"><i class="nav-icon fa fa-circle"></i> 定期稼働集計</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.sales.summary.wholesale")}}"><i class="nav-icon fa fa-circle"></i> 卸集計</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.sales.summary.age")}}"><i class="nav-icon fa fa-circle"></i> 年代別集計</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.sales.summary.payment")}}"><i class="nav-icon fa fa-circle"></i> 支払い方法別集計</a></li>

            </ul>
        </li>
        {{-- ステップDM --}}
        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon icon-chart text-info"></i> ステップDM</a>
            <ul class="nav-dropdown-items">
                <li class="nav-item"><a class="nav-link" href="{{route("admin.stepdm.download")}}"><i class="nav-icon fa fa-circle"></i> ダウンロード</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.stepdm.setting")}}"><i class="nav-icon fa fa-circle"></i> 設定閲覧</a></li>
            </ul>
        </li>


        {{-- メール設定 --}}
        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon icon-envelope text-info"></i> メール設定</a>
            <ul class="nav-dropdown-items">
                <li class="nav-item"><a class="nav-link" href="{{route("admin.mail.template")}}"><i class="nav-icon fa fa-circle"></i> テンプレート一覧</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.mail.layout")}}"><i class="nav-icon fa fa-circle"></i> レイアウト一覧</a></li>
            </ul>
        </li>

        {{-- CSVインポート --}}
        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon icon-cloud-upload text-info"></i> CSVインポート</a>
            <ul class="nav-dropdown-items">
                <li class="nav-item"><a class="nav-link" href="{{route("admin.media.import")}}"><i class="nav-icon fa fa-circle"></i> 広告媒体</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.order.delivery_slip")}}"><i class="nav-icon fa fa-circle"></i> 伝票番号</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.import.call_center")}}"><i class="nav-icon fa fa-circle"></i> コールセンター</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.import.unreachable_email")}}"><i class="nav-icon fa fa-circle"></i> メルマガ不達</a></li>
            </ul>
        </li>


        {{-- システム設定 --}}
        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon {{__("admin.icon_class.setting")}} text-info"></i> システム</a>
            <ul class="nav-dropdown-items">
                <li class="nav-item"><a class="nav-link" href="{{route("admin.account.list")}}"><i class="nav-icon fa fa-circle"></i> アカウント一覧</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.account.create")}}"><i class="nav-icon fa fa-circle"></i> アカウント作成</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.mail.system")}}"><i class="nav-icon fa fa-circle"></i> メール共通設定</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.system.holiday")}}"><i class="nav-icon fa fa-circle"></i> 休日・祝祭日設定</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.system.tax")}}"><i class="nav-icon fa fa-circle"></i> 消費税設定</a></li>
            </ul>
        </li>

        {{-- ユーザー画面一覧/ web ミドルウェアのルーティング一覧を表示する感じ？ --}}
        {{--
        <li class="nav-title">System(仮)</li>
        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon icon-refresh text-info"></i> バッチ処理</a>
            <ul class="nav-dropdown-items">
                <li class="nav-item"><a class="nav-link" href="{{route("admin.mail.system")}}"><i class="nav-icon fa fa-circle"></i> バッチ処理一覧</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.mail.template")}}"><i class="nav-icon fa fa-circle"></i> バッチログ</a></li>
            </ul>
        </li>

        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon icon-puzzle text-info"></i> イベント</a>

            <ul class="nav-dropdown-items">
                <li class="nav-item"><a class="nav-link" href="{{route("admin.mail.system")}}"><i class="nav-icon fa fa-circle"></i> イベント一覧</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route("admin.mail.template")}}"><i class="nav-icon fa fa-circle"></i> イベントログ</a></li>
            </ul>
        </li>
        --}}
    </ul>
</nav>
<button class="sidebar-minimizer brand-minimizer" type="button"></button>
