<div class="card">
    <div class="card-header">
        {{__("admin.item_name.common.search_result")}} {{ $orders->total() }}{{__("admin.item_name.common.unit")}}
        <div class="card-header-actions">
            <button @click.prevent="csv_download" class="btn btn-sm btn-primary"><i class="{{__("admin.icon_class.csv_download")}}"></i>&nbsp;{{__("admin.operation.csv_download")}}</button>

            <a class="card-header-action btn-setting" href="{{route("admin.system.csv_setting",['id'=>3])}}">
                <button class="btn btn-sm btn-secondary"><i class="{{__("admin.icon_class.csv_setting")}}"></i>&nbsp;{{__("admin.operation.csv_setting")}}</button>
            </a>
        </div>
    </div>

    <div class="card-body">
        @include('admin.components.common.pagination', ['results'=>$orders])
        <table class="table table-sm">
            <thead>
            <tr>
                {{-- 定期ID/定期回 --}}
                <th>
                    {{__("admin.item_name.periodic.id")}}<br />
                    {{__("admin.item_name.periodic.count")}}
                </th>

                {{-- 顧客ID/購入者名 --}}
                <th>{{__("admin.item_name.customer.id")}}<br>
                    {{__("admin.item_name.periodic.name")}}
                </th>

                {{-- 商品名 --}}
                <th>{{__("admin.item_name.product.name")}}</th>

                {{-- 金額 --}}
                <th>{{__("admin.item_name.order.payment_total")}}
                    <a @click.prevent="search_results('', 'last_payment_total_asc')"><i class="fa fa-sort-amount-asc" :class="{ fa_deactive: sort!='last_payment_total_asc' }"></i></a>
                    <a @click.prevent="search_results('', 'last_payment_total_desc')"><i class="fa fa-sort-amount-desc" :class="{ fa_deactive: sort!='last_payment_total_desc' }"></i></a>
                </th>

                {{-- 間隔 --}}
                <th>{{__("admin.item_name.periodic.interval")}}
                    <a @click.prevent="search_results('', 'interval_days_asc')"><i class="fa fa-sort-amount-asc" :class="{ fa_deactive: sort!='interval_days_asc' }"></i></a>
                    <a @click.prevent="search_results('', 'interval_days_desc')"><i class="fa fa-sort-amount-desc" :class="{ fa_deactive: sort!='interval_days_desc' }"></i></a>
                </th>

                {{-- 支払い方法 --}}
                <th class="d-none d-xl-table-cell">{{__("admin.item_name.order.payment_method")}}</th>

                {{-- SHOPメモ --}}
                <th>{{__("admin.item_name.common.shop_memo")}}</th>

                {{-- 前回到着/次回到着 --}}
                <th>
                    {{__("admin.item_name.periodic.prev_create_date")}}<br />
                    {{__("admin.item_name.periodic.next_create_date")}}
                </th>

                {{-- 稼働状況/停止フラグ --}}
                <th>{{__("admin.item_name.periodic.status")}}<br>
                    {{__("admin.item_name.periodic.stop_flag")}}
                </th>

                <th>{{__("admin.item_name.periodic.duplicate")}}</th>
                @if($deletable)
                    <th>{{__("admin.operation.delete")}}</th>
                @endif
            </tr>
            </thead>

            <tbody>
                @if (count($orders) > 0)
                    @foreach($orders as $count => $order)
                        @include('admin.components.periodic.search_result_item', ['order'=>$order])
                    @endforeach
                @endif
            </tbody>

        </table>
        @include('admin.components.common.pagination', ['results'=>$orders])
    </div>

</div>