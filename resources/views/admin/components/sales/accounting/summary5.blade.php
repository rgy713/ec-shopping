@inject('stockKeepingUnitList', 'App\Common\KeyValueLists\StockKeepingUnitList')

<div class="card">
    <div class="card-header">
        5.無料プレゼント
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
                        <th>件数</th>
                        <th>本数</th>
                    </tr>
                    </thead>
                    @foreach($stockKeepingUnitList as $stockKeepingUnit)
                        <tr>
                            <th>{{$stockKeepingUnit}}</th>
                            <td>件</td>
                            <td>本</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>