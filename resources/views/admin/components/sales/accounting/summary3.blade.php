<div class="card">
    <div class="card-header">
        {{ $head_name }}
        <div class="card-header-actions">

        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <table class="table table-sm table-bordered">
                    <tr>
                        <th></th>
                        <th>{{__("admin.item_name.sales.total1")}}</th>
                        <th>{{__("admin.item_name.common.ratio")}}</th>
                    </tr>

                    <tr>
                        <th>{{__("admin.item_name.common.new")}}</th>
                        <td>@if (isset($summary->new_total)) {{$summary->new_total}}{{__("admin.item_name.common.wen")}} @endif</td>
                        <td>@if (isset($summary->total_total) && $summary->total_total > 0) {{round($summary->new_total / $summary->total_total * 100, 2)}} @else 0 @endif%</td>
                    </tr>

                    <tr>
                        <th>{{__("admin.item_name.sales.peat")}}</th>
                        <td>@if (isset($summary->peat_total)) {{$summary->peat_total}}{{__("admin.item_name.common.wen")}} @endif</td>
                        <td>@if (isset($summary->total_total) && $summary->total_total > 0) {{round($summary->peat_total / $summary->total_total * 100, 2)}} @else 0 @endif%</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

