{{-- マーケティング集計.1 --}}
<div class="card">
    <div class="card-header">
        {{ $head_name }}
        <div class="card-header-actions">
            <button @click.prevent="csv_download({{$marketing_type}})" class="btn btn-sm btn-primary"><i class="{{__("admin.icon_class.csv_download")}}"></i>&nbsp;{{__("admin.operation.csv_download")}}</button>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-12">

                <table class="table table-sm table-bordered">
                    <tr>
                        <th>{{__("admin.item_name.sales.accounting_total")}}</th>
                        <td colspan="2">@if (isset($summary->total_total)) {{$summary->total_total}}{{__("admin.item_name.common.wen")}} @endif</td>
                    </tr>

                    <tr>
                        <th>{{__("admin.item_name.sales.accounting_delivery")}}</th>
                        <td colspan="2">@if (isset($summary->delivery_total)) {{$summary->delivery_total}}{{__("admin.item_name.common.wen")}} @endif</td>
                    </tr>

                    <tr>
                        <th></th>
                        <th>{{__("admin.item_name.common.new")}}</th>
                        <th>{{__("admin.item_name.sales.peat")}}</th>
                    </tr>

                    <tr>
                        <th>{{__("admin.item_name.sales.sales")}}</th>
                        <td>@if (isset($summary->new_total)) {{$summary->new_total}}{{__("admin.item_name.common.wen")}} @endif</td>
                        <td>@if (isset($summary->peat_total)) {{$summary->peat_total}}{{__("admin.item_name.common.wen")}} @endif</td>
                    </tr>

                    <tr>
                        <th>{{__("admin.item_name.common.customer_count")}}</th>
                        <td>@if (isset($summary->new_customer_count)) {{$summary->new_customer_count}}{{__("admin.item_name.common.man_unit1")}} @endif</td>
                        <td>@if (isset($summary->peat_customer_count)) {{$summary->peat_customer_count}}{{__("admin.item_name.common.man_unit1")}} @endif</td>
                    </tr>

                    <tr>
                        <th>{{__("admin.item_name.sales.count")}}</th>
                        <td>@if (isset($summary->new_count)) {{$summary->new_count}}{{__("admin.item_name.common.unit")}} @endif</td>
                        <td>@if (isset($summary->peat_count)) {{$summary->peat_count}}{{__("admin.item_name.common.unit")}} @endif</td>
                    </tr>

                    <tr>
                        <th>{{__("admin.item_name.sales.avg")}}</th>
                        <td>@if (isset($summary->new_count) && $summary->new_count > 0) {{round($summary->new_total / $summary->new_count, 2)}} @else 0 @endif{{__("admin.item_name.common.wen")}}</td>
                        <td>@if (isset($summary->peat_count) && $summary->peat_count > 0) {{round($summary->peat_total / $summary->peat_count, 2)}} @else 0 @endif{{__("admin.item_name.common.wen")}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>