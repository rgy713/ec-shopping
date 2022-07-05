<div class="card">
    <div class="card-header">
        登録タグ一覧
        <div class="card-header-actions">
            <a href="{{route("admin.media.tag.create")}}"><button class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i>&nbsp;新規作成</button></a>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-sm table-hover">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>{{__("admin.item_name.tag.name")}}</th>
                <th>{{__("admin.item_name.common.creator")}}</th>
                <th>{{__("admin.item_name.common.remark")}}</th>
                <th>ページ数</th>
                <th>{{__("admin.item_name.tag.enabled")}}</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>1</td>
                <td>GDNタグ</td>
                <td>ヴァンクリーフ</td>
                <td>設置者が内容についてコメントを記述します。</td>
                <td>5</td>
                <td>有効</td>
                <td><a href="{{route("admin.media.tag.edit",["id"=>1])}}"><button class="btn btn-secondary btn-sm"><i class="fa fa-edit"></i>&nbsp;編集</button></a></td>
            </tr>
            <tr class="table-secondary">
                <td>2</td>
                <td>Gunocyタグ</td>
                <td>ヴァンクリーフ</td>
                <td>設置者が内容についてコメントを記述します。</td>
                <td>10</td>
                <td>
                    無効
                </td>
                <td><a href="{{route("admin.media.tag.edit",["id"=>1])}}"><button class="btn btn-secondary btn-sm"><i class="fa fa-edit"></i>&nbsp;編集</button></a></td>
            </tr>
            <tr>
                <td>3</td>
                <td>adEbis共通タグ</td>
                <td>ギャプライズ</td>
                <td>設置者が内容についてコメントを記述します。</td>
                <td>15</td>
                <td>有効</td>
                <td><a href="{{route("admin.media.tag.edit",["id"=>1])}}"><button class="btn btn-secondary btn-sm"><i class="fa fa-edit"></i>&nbsp;編集</button></a></td>

            </tr>
            </tbody>
        </table>

    </div>
</div>