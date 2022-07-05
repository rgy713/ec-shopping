{{-- 定期稼働者推移 --}}
<div class="card">
    <div class="card-header">
        {{ $header_name }}
        <div class="card-header-actions">

        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <table class="table table-sm table-bordered">
                    <tr>
                        <th></th>
                        <th>{{__('admin.item_name.sales.active_count')}}</th>
                        <th>{{__('admin.item_name.sales.stop_count')}}</th>
                    </tr>

                    <tr>
                        <th>{{__('admin.item_name.common.total')}}</th>
                        <td>@if (isset($sum->active_count) && $item->active_count > 0 ) {{ $sum->active_count }}{{__('admin.item_name.common.unit')}} @endif</td>
                        <td>@if (isset($sum->stop_count) && $item->stop_count > 0 ) {{ $sum->stop_count }}{{__('admin.item_name.common.unit')}} @endif</td>
                    </tr>

                    @foreach($summary as $item)
                        <tr>
                            <th>{{$item->counts}}{{__('admin.item_name.common.count_unit')}}@if ($item->counts == 21) {{__('admin.item_name.common.greater')}} @endif</th>
                            <td>@if (isset($item->active_count) && $item->active_count > 0 ) {{ $item->active_count }}{{__('admin.item_name.common.unit')}} @endif</td>
                            <td>@if (isset($item->stop_count) && $item->stop_count > 0 ) {{ $item->stop_count }}{{__('admin.item_name.common.unit')}} @endif</td>
                        </tr>
                    @endforeach

                </table>
            </div>
        </div>
    </div>
</div>