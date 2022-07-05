@inject('stockKeepingUnitList', 'App\Common\KeyValueLists\StockKeepingUnitList')

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
                    <thead>
                    <tr>
                        <th></th>
                        <th>{{__("admin.item_name.sales.count")}}</th>
                        <th>{{__("admin.item_name.common.unit_count")}}</th>
                    </tr>
                    </thead>
                    @foreach($summary as $item)
                        <tr>
                            <th>{{$item->name}}</th>
                            <td>{{$item->count}}{{__("admin.item_name.common.unit")}}</td>
                            <td>{{$item->total}}{{$item->unit_name}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

