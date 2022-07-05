{{-- 1.売上計上 --}}
<div class="card">
    <div class="card-header">
        {{ $head_name }}
        <div class="card-header-actions">
            <button @click.prevent="csv_download({{$accounting_type}})" class="btn btn-sm btn-primary"><i class="fa fa-download"></i>&nbsp;{{__("admin.operation.download")}}</button>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <table class="table table-sm table-bordered">
                    <tr>
                        <th></th>
                        <th>{{__("admin.item_name.common.num_result1")}}</th>
                        <th>{{__("admin.item_name.sales.total1")}}</th>
                    </tr>
                    <tr>
                        <th>{{__("admin.item_name.sales.accounting_total")}}</th>
                        <td>@if (isset($summary->total_count)) {{$summary->total_count}}{{__("admin.item_name.common.unit")}} @endif</td>
                        <td>@if (isset($summary->total_total)) {{$summary->total_total}}{{__("admin.item_name.common.wen")}} @endif</td>
                    </tr>
                    <tr>
                        <th>{{__("admin.item_name.sales.accounting_net")}}</th>
                        <td></td>
                        <td>@if (isset($summary->net_total)) {{$summary->net_total}}{{__("admin.item_name.common.wen")}} @endif</td>
                    </tr>

                    <tr>
                        <th>{{__("admin.item_name.sales.accounting_delivery")}}</th>
                        <td></td>
                        <td>@if (isset($summary->delivery_total)) {{$summary->delivery_total}}{{__("admin.item_name.common.wen")}} @endif</td>
                    </tr>

                    <tr>
                        <th>{{__("admin.item_name.sales.accounting_first")}}</th>
                        <td>@if (isset($summary->first_count)) {{$summary->first_count}}{{__("admin.item_name.common.unit")}} @endif</td>
                        <td>@if (isset($summary->first_total)) {{$summary->first_total}}{{__("admin.item_name.common.wen")}} @endif</td>
                    </tr>

                    <tr>
                        <th>{{__("admin.item_name.sales.accounting_peat")}}</th>
                        <td>@if (isset($summary->peat_count)) {{$summary->peat_count}}{{__("admin.item_name.common.unit")}} @endif</td>
                        <td>@if (isset($summary->peat_total)) {{$summary->peat_total}}{{__("admin.item_name.common.wen")}} @endif</td>
                    </tr>

                    <tr>
                        <th>{{__("admin.item_name.sales.accounting_periodic")}}</th>
                        <td>@if (isset($summary->periodic_count)) {{$summary->periodic_count}}{{__("admin.item_name.common.unit")}} @endif</td>
                        <td>@if (isset($summary->periodic_total)) {{$summary->periodic_total}}{{__("admin.item_name.common.wen")}} @endif</td>
                    </tr>

                    <tr>
                        <th>{{__("admin.item_name.sales.accounting_special")}}</th>
                        <td></td>
                        <td>@if (isset($summary->special_total)) {{$summary->special_total}}{{__("admin.item_name.common.wen")}} @endif</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>