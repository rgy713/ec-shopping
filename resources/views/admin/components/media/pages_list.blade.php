{{-- タグ設置対象のページ一覧表示 --}}
{{-- $buttonEnable:true/false ヘッダ部分のボタンを有効化する --}}
{{-- $checkboxEnable:true/false チェックボックスを有効化する --}}


<div class="card">
    <div class="card-header">
        設置対象ページ
        <div class="card-header-actions">
            @if($buttonEnable)
            <button class="btn btn-sm btn-secondary"><i class="icon-check"></i>&nbsp;全選択/解除</button>
            @endif
        </div>
    </div>

    <div class="card-body">
        <table class="table table-sm table-hover">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>{{__("admin.item_name.page.group")}}</th>
                <th>{{__("admin.item_name.page.name")}}</th>
                <th>{{__("admin.item_name.page.path")}}</th>
                <th>{{__("admin.item_name.page.count_of_tags")}}</th>
                <th>{{__("admin.item_name.page.pc")}}</th>
                <th>{{__("admin.item_name.page.sp")}}</th>
                @if($linkEnable)
                <th></th>
                @endif

            </tr>
            </thead>
            <tbody>
            <tr>
                <td>1</td>
                <td>購入完了画面</td>
                <td>購入フロー</td>
                <td>/shopping/complete</td>
                <td>5</td>
                @if($checkboxEnable)
                    <td><input type="checkbox" checked="true"></td>
                    <td><input type="checkbox" checked="true"></td>
                @else
                    <td>◯</td>
                    <td>◯</td>
                @endif

                @if($linkEnable)
                    <td><a href="{{route("admin.media.tag.page.info",['id'=>1])}}"><button class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i>{{__("admin.operation.edit")}}</button></a></td>
                @endif

            </tr>

            <tr>
                <td>2</td>
                <td>購入フロー</td>
                <td>支払い方法選択画面</td>
                <td>/shopping/payment</td>
                <td>10</td>
                @if($checkboxEnable)
                    <td><input type="checkbox" checked="true"></td>
                    <td><input type="checkbox" checked="true"></td>
                @else
                    <td>◯</td>
                    <td>◯</td>
                @endif
                @if($linkEnable)
                    <td><a href="{{route("admin.media.tag.page.info",['id'=>1])}}"><button class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i>{{__("admin.operation.edit")}}</button></a></td>
                @endif
            </tr>

            <tr class="table-secondary">
                <td>3</td>
                <td>その他</td>
                <td>トップページ</td>
                <td>/</td>
                <td>15</td>
                @if($checkboxEnable)
                    <td><input type="checkbox" checked="true"></td>
                    <td><input type="checkbox" checked="true"></td>
                @else
                    <td>◯</td>
                    <td>◯</td>
                @endif
                @if($linkEnable)
                    <td><a href="{{route("admin.media.tag.page.info",['id'=>1])}}"><button class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i>{{__("admin.operation.edit")}}</button></a></td>
                @endif
            </tr>

            <tr>
                <td>4</td>
                <td>マイページ</td>
                <td>マイページログイン</td>
                <td>/mypage/login</td>
                <td>20</td>
                @if($checkboxEnable)
                    <td><input type="checkbox" checked="true"></td>
                    <td><input type="checkbox" checked="true"></td>
                @else
                    <td>◯</td>
                    <td>◯</td>
                @endif
                @if($linkEnable)
                    <td><a href="{{route("admin.media.tag.page.info",['id'=>1])}}"><button class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i>{{__("admin.operation.edit")}}</button></a></td>
                @endif
            </tr>

            </tbody>
        </table>

    </div>
</div>