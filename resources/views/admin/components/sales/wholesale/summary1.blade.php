{{-- 卸集計.1 --}}
<div class="card">
    <div class="card-header">
        1.{{__("admin.page_header_name.summary_wholesale1")}}
        <div class="card-header-actions">
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-12">

                <table class="table table-sm table-bordered">
                    <tr>
                        <th>{{__("admin.item_name.sales.customer_name")}}</th>
                        <th>{{__("admin.item_name.sales.count")}}</th>
                        <th>{{__("admin.item_name.sales.total")}}</th>
                        <th>{{__("admin.item_name.sales.operation")}}</th>
                    </tr>

                    @foreach($result as $count=>$summary)
                        <tr>
                            <th>{{$summary->name01}} {{$summary->name02}}</th>
                            <td>{{$summary->count}}</td>
                            <td>{{$summary->total}}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" @click.prevent="csv_download({{$summary->customer_id}})">
                                    <i class="{{__("admin.icon_class.csv_download")}}"></i>&nbsp;{{__("admin.operation.csv_download")}}
                                </button>
                            </td>
                        </tr>
                    @endforeach

                    <tr>
                        <th>{{__("admin.item_name.order.total")}}</th>
                        <td>{{isset($sum->count) ? $sum->count : 0}}</td>
                        <td>{{isset($sum->total) ? $sum->total : 0}}</td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
</div>