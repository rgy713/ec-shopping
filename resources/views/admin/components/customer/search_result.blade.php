{{-- 顧客検索結果 --}}
@inject('customerStatusList', 'App\Common\KeyValueLists\CustomerStatusList')
@inject('prefectureList', 'App\Common\KeyValueLists\PrefectureList')

<div class="card">
    <div class="card-header">
        {{__("admin.item_name.common.search_result")}} @if (method_exists($customers, 'total')) {{ $customers->total() }} @else 0 @endif{{__("admin.item_name.common.unit")}}
        <div class="card-header-actions">
            @if($csvButton)
                <div class="card-header-actions">
                    <a class="card-header-action btn-close" href="#">
                        <button class="btn btn-sm btn-primary" type="button" onclick="customerCsvDownload()"><i class="{{__("admin.icon_class.csv_download")}}"></i>&nbsp;{{__("admin.operation.csv_download")}}</button>
                    </a>
                    <a class="card-header-action btn-setting" href="{{route("admin.system.csv_setting",['id'=>1])}}">
                        <button class="btn btn-sm btn-secondary"><i class="{{__("admin.icon_class.csv_setting")}}"></i>&nbsp;{{__("admin.operation.csv_setting")}}</button>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="card-body">
        @include('admin.components.common.pagination', ['results'=>$customers])
        <table class="table table-sm">
            <thead>
            <tr>
                <th>{{__("admin.item_name.customer.type")}}</th>
                <th>{{__("admin.item_name.customer.id")}}<br>{{__("admin.item_name.address.prefecture")}}</th>
                <th>{{__("common.item_name.address.name")}}<br>{{__("common.item_name.address.kana")}}</th>
                <th>{{__("common.item_name.address.tel")}}</th>
                <th>{{__("admin.item_name.customer.email")}}</th>
                <th class="d-none d-xl-table-cell">{{__("admin.item_name.customer.birthday")}}</th>
                <th class="d-none d-xl-table-cell">{{__("admin.item_name.customer.buy_times")}}<br>{{__("admin.item_name.customer.buy_volume")}}</th>
                <th class="d-none d-xl-table-cell">{{__("admin.item_name.media.code")}}</th>
                <th>{{__("admin.operation.delete")}}
                </th>
            </tr>
            </thead>

            <tbody>
                @if (count($customers) > 0)
                    @foreach($customers as $count => $customer)
                        @include('admin.components.customer.search_result_item', ['customer'=>$customer])
                    @endforeach
                @else
                    @include('admin.components.customer.search_result_empty')
                @endif
            </tbody>

        </table>

        @include('admin.components.common.pagination', ['results'=>$customers])

    </div>
</div>

