{{-- 受注検索結果 --}}
@inject('orderStatusList', 'App\Common\KeyValueLists\OrderStatusList')
@inject('paymentList', 'App\Common\KeyValueLists\PaymentList')

<div class="card">
    <div class="card-header">
        {{__("admin.item_name.common.search_result")}} {{ $orders->total() }}{{__("admin.item_name.common.unit")}}
        <div class="card-header-actions">
            <button @click.prevent="csv_download" class="btn btn-sm btn-primary"><i class="{{__("admin.icon_class.csv_download")}}"></i>&nbsp;{{__("admin.operation.csv_download")}}</button>

            @if(!$isShipping)
                <a class="card-header-action btn-setting" href="{{route("admin.system.csv_setting",['id'=>2])}}">
                    <button type="button" class="btn btn-sm btn-secondary"><i class="{{__("admin.icon_class.csv_setting")}}"></i>&nbsp;{{__("admin.operation.csv_setting")}}</button>
                </a>

                <div class="form-check form-check-inline">
                    <select class="form-control form-control-sm" name="new_status_id" required>
                        @foreach($orderStatusList as $id => $name)
                            <option value="{{$id}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>

                <button @click.prevent="update_order_statuses" class="btn btn-sm btn-primary">{{__("admin.operation.batch_update")}}</button>
            @else
                <a class="card-header-action btn-setting" href="{{route("admin.system.csv_setting",['id'=>4])}}">
                    <button type="button" class="btn btn-sm btn-secondary"><i class="{{__("admin.icon_class.csv_setting")}}"></i>&nbsp;{{__("admin.operation.csv_setting")}}</button>
                </a>

                <button class="btn btn-sm btn-primary" type="button" v-on:click="delivery_pdf_list()">{{__("admin.operation.export_pdf")}}</button>
            @endif

        </div>
    </div>

    <div class="card-body collapse multi-collapse show">
        @include('admin.components.common.pagination', ['results'=>$orders])
        <table class="table table-sm" id="search_list_table">
            <thead>
            <tr>
                <th class="d-none d-xl-table-cell">{{__("admin.item_name.order.create_date")}}</th>
                <th>{{__("admin.item_name.order.id")}}<br>
                    <a @click.prevent="search_results('', 'id_asc')"><i class="fa fa-sort-amount-asc" :class="{ fa_deactive: sort!='id_asc' }"></i></a>
                    <a @click.prevent="search_results('', 'id_desc')"><i class="fa fa-sort-amount-desc" :class="{ fa_deactive: sort!='id_desc' }"></i></a>
                </th>
                <th>{{__("common.item_name.address.name")}}</th>
                <th class="d-none d-xl-table-cell">{{__("admin.item_name.order.payment_method")}}</th>
                <th>{{__("admin.item_name.order.payment_total")}}<br>
                    <a @click.prevent="search_results('', 'payment_total_asc')"><i class="fa fa-sort-amount-asc" :class="{ fa_deactive: sort!='payment_total_asc' }"></i></a>
                    <a @click.prevent="search_results('', 'payment_total_desc')"><i class="fa fa-sort-amount-desc" :class="{ fa_deactive: sort!='payment_total_desc' }"></i></a>
                </th>
                <th class="d-none d-xl-table-cell">{{__("admin.item_name.order.payment")}}</th>
                <th class="d-none d-xl-table-cell">{{__('admin.item_name.shipping.estimated_arrival_date')}}</th>
                <th>
                    @if($isShipping)
                        {{-- 配送管理 --}}
                        {{__("admin.item_name.order.status")}}
                    @else
                        {{-- 受注検索 --}}
                        <div class="form-check form-check-inline">
                            <input id="order-status-checkbox" class="form-check-input" type="checkbox" v-model="all_status_chk" @change="check_all_status">
                            {{__("admin.item_name.order.status")}}
                        </div>
                    @endif
                </th>

                <th>{{__("admin.item_name.shipping.delivery_slip_num")}}</th>

                @if(!$isShipping)
                    {{-- 受注検索 --}}
                    @if($editable) <th>{{__("admin.operation.update")}}</th> @endif
                    <th><button @click.prevent="bundle_items" class="btn btn-sm btn-primary">{{__("admin.item_name.order.bundled")}}</button></th>
                    <th class="d-none d-xl-table-cell">{{__("admin.item_name.common.email")}}</th>
                    @if($editable) <th>{{__("admin.operation.edit")}}</th> @endif
                    @if($deletable) <th><button @click.prevent="delete_items" class="btn btn-sm btn-primary">{{__("admin.operation.delete")}}</button></th> @endif
                @else
                    {{-- 配送管理 --}}
                    <th>{{__("admin.item_name.order.summary_delivery")}}</th>
                    <th>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox"  v-model="all_export_chk" @change="check_all_export">
                            {{__("admin.operation.form")}}
                        </div>
                    </th>
                @endif

            </tr>
            </thead>

            <tbody>
                @if (count($orders) > 0)
                    @foreach($orders as $count => $order)
                        @include('admin.components.order.search_result_item', ['order'=>$order])
                    @endforeach
                @endif
            </tbody>

        </table>
        @include('admin.components.common.pagination', ['results'=>$orders])
    </div>

    <div class="card-footer collapse multi-collapse show">
    </div>
</div>
