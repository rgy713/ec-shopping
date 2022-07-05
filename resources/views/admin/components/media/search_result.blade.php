{{-- 広告検索結果 --}}
<div class="card">
    <div class="card-header">
        {{__("admin.item_name.common.search_result")}} {{ $medias->total() }}{{__("admin.item_name.common.unit")}}
        <div class="card-header-actions">
            <a href="{{route('admin.media.create')}}" class="btn btn-sm">{{__("admin.item_name.media.create")}}</a>
            <button @click.prevent="csv_download" class="btn btn-sm btn-primary">
                <i class="{{__("admin.icon_class.csv_download")}}"></i>&nbsp;{{__("admin.operation.csv_download")}}
            </button>
        </div>
    </div>

    <div class="card-body">
        @include('admin.components.common.pagination', ['results'=>$medias])
        <table class="table table-sm table-hover">
            <thead class="thead-light">
            <tr>
                <th>
                    {{__("admin.item_name.media.code")}}
                    <a @click.prevent="search_results('', 'code_asc')"><i class="fa fa-sort-amount-asc" :class="{ fa_deactive: sort!='code_asc' }"></i></a>
                    <a @click.prevent="search_results('', 'code_desc')"><i class="fa fa-sort-amount-desc" :class="{ fa_deactive: sort!='code_desc' }"></i></a>
                </th>

                <th class="d-none d-xl-table-cell">{{__("admin.item_name.media.broadcaster")}}</th>

                <th>{{__("admin.item_name.media.name")}}</th>

                <th class="d-none d-xl-table-cell">{{__("admin.item_name.media.cost")}}</th>

                <th>
                    {{__("admin.item_name.media.start_date")}}
                    <a @click.prevent="search_results('', 'date_asc')"><i class="fa fa-sort-amount-asc" :class="{ fa_deactive: sort!='date_asc' }"></i></a>
                    <a @click.prevent="search_results('', 'date_desc')"><i class="fa fa-sort-amount-desc" :class="{ fa_deactive: sort!='date_desc' }"></i></a>
                </th>

                <th class="d-none d-xl-table-cell">
                    {{__("admin.item_name.media.start_time")}}
                </th>

                <th class="d-none d-xl-table-cell">{{__('admin.item_name.media.broadcast_minutes')}}</th>
                <th class="d-none d-xl-table-cell">{{__('admin.item_name.media.broadcast_duration')}}</th>

                <th class="d-none d-xl-table-cell">{{__('admin.item_name.product.lineup')}}</th>

                <th>
                    {{__('admin.item_name.media.customer_count')}}
                    <a @click.prevent="search_results('', 'customer_count_asc')"><i class="fa fa-sort-amount-asc" :class="{ fa_deactive: sort!='customer_count_asc' }"></i></a>
                    <a @click.prevent="search_results('', 'customer_count_desc')"><i class="fa fa-sort-amount-desc" :class="{ fa_deactive: sort!='customer_count_desc' }"></i></a>
                </th>

                @if (isset($editable) && $editable)
                    <th>{{__("admin.operation.delete")}}</th>
                @endif
            </tr>
            </thead>

            <tbody>
                @if (count($medias) > 0)
                    @foreach($medias as $count => $media)
                        @include('admin.components.media.search_result_item', ['media'=>$media])
                    @endforeach
                @endif
            </tbody>
        </table>
        @include('admin.components.common.pagination', ['results'=>$medias])
    </div>

</div>
