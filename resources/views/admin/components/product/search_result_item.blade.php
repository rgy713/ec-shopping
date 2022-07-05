{{-- 商品検索結果１行 --}}

{{-- 非公開の場合に背景色グレーに変更 --}}
<tr class="@if(!$product->user_visible) table-secondary @endif">
    {{-- 商品ID --}}
    <td>{{$product->id}}</td>

    {{-- 商品画像 --}}
    <td><img src="{{ secure_asset("storage/products/image/".$product->id) }}" width="100" style="object-fit: cover;" alt="{{ $product->code }}"> </td>

    {{-- 価格 --}}
    <td>{{$product->price}}</td>

    {{-- 本数 --}}
    <td>{{$product->volume}}</td>

    {{-- 商品コード/商品名 --}}
    <td>
        {{$product->code}}<br />
        {{$product->name}}
    </td>

    {{-- 公開/非公開 --}}
    <td>{{$productVisibleList[$product->user_visible]}}</td>

    {{-- 操作ボタン --}}
    <td>
        @if (isset($editable) && $editable)
            <a href="{{route("admin.product.edit",["id"=>$product->id])}}"><button class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i>&nbsp;{{__("admin.operation.edit")}}</button></a>
        @endif
    </td>
    <td>
        @if (isset($editable) && $editable)
            <button @click.prevent="delete_item({{$product->id}})" class="btn btn-sm btn-secondary" @if ($product->order_detail_count > 0 || $product->periodic_order_detail_count > 0) disabled @endif>
                <i class="fa fa-remove"></i>&nbsp;{{__("admin.operation.delete")}}
            </button>
        @endif
    </td>
    <td>
        @if (isset($editable) && $editable)
            <a href="{{route("admin.product.create",["id"=>$product->id])}}"><button class="btn btn-sm btn-secondary"><i class="fa fa-copy"></i>&nbsp;{{__("admin.operation.copy")}}</button></a>
        @endif
    </td>
</tr>
