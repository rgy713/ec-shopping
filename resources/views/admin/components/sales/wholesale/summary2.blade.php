{{-- 卸集計.2 --}}
<div class="card">
    <div class="card-header">
        2.{{__("admin.page_header_name.summary_wholesale2")}}
        <div class="card-header-actions">
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-12">

                <table class="table table-sm table-bordered">
                    <tr>
                        <th>#</th>
                        <th>{{__("admin.item_name.sales.sku")}}</th>
                        <th>{{__("admin.item_name.sales.count1")}}</th>
                    </tr>

                    @foreach($result as $count=>$summary)
                        <tr>
                            <td>{{$summary->id}}</td>
                            <td>{{$summary->name}}</td>
                            <td>{{$summary->count}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>