{{-- 商品検索結果 --}}
@inject('productVisibleList', 'App\Common\KeyValueLists\ProductVisibleList')

<div class="card">
    <div class="card-header">
         {{__("admin.item_name.product.search_result")}} {{ $products->total() }}{{__("admin.item_name.common.unit")}}
        <div class="card-header-actions">
        </div>

    </div>

    <div class="card-body">
        @include('admin.components.common.pagination', ['results'=>$products])
        <table class="table table-sm">
            <thead>
                <tr>
                    {{-- 商品ID --}}
                    <th>{{__("admin.item_name.product.id")}}</th>

                    {{-- 商品画像 --}}
                    <th>{{__("admin.item_name.product.image")}}</th>

                    {{-- 価格 --}}
                    <th>{{__("admin.item_name.product.price")}}
                        <a @click.prevent="search_results('', 'price_asc')"><i class="fa fa-sort-amount-asc" :class="{ fa_deactive: sort!='price_asc' }"></i></a>
                        <a @click.prevent="search_results('', 'price_desc')"><i class="fa fa-sort-amount-desc" :class="{ fa_deactive: sort!='price_desc' }"></i></a>
                    </th>

                    {{-- 本数 --}}
                    <th>{{__("admin.item_name.product.volume")}}
                        <a @click.prevent="search_results('', 'volume_asc')"><i class="fa fa-sort-amount-asc" :class="{ fa_deactive: sort!='volume_asc' }"></i></a>
                        <a @click.prevent="search_results('', 'volume_desc')"><i class="fa fa-sort-amount-desc" :class="{ fa_deactive: sort!='volume_desc' }"></i></a>
                    </th>

                    {{-- 商品コード/商品名 --}}
                    <th>
                        {{__("admin.item_name.product.code")}}<br />
                        {{__("admin.item_name.product.name")}}
                    </th>

                    {{-- 公開/非公開 --}}
                    <th>{{__("admin.item_name.product.visible")}}</th>

                    {{-- 操作ボタン --}}
                    <th>{{__("admin.operation.edit")}}
                    <th>{{__("admin.operation.delete")}}</th>
                    <th>{{__("admin.operation.copy")}}</th>
                </tr>
            </thead>

            <tbody>
                @if (count($products) > 0)
                    @foreach($products as $count => $product)
                        @include('admin.components.product.search_result_item', ['product'=>$product])
                    @endforeach
                @else
                    @include('admin.components.product.search_result_empty')
                @endif
            </tbody>

        </table>

        @include('admin.components.common.pagination', ['results'=>$products])
    </div>

</div>