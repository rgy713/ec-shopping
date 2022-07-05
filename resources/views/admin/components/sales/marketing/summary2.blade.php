{{-- マーケティング集計.2 --}}
@inject('itemLineupList', 'App\Common\KeyValueLists\ItemLineupList')

@php
    $itemLineupListHeader = [1, 3, 4 ,5];

    $itemNameList=[
        "1_0_1"=>"LP/数量1",
        "1_1_1"=>"直定期/数量1",
        "1_1_2"=>"直定期/数量2",
        "0_1_1"=>"定期/数量1",
        "0_1_2"=>"定期/数量2",
        "0_0_1"=>"通常/数量1",
        "0_0_2"=>"通常/数量2",
        "0_0_3"=>"通常/数量3",
        "sum"=>"合計数",
        "sum_total"=>"合計個数",
    ];
@endphp

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
                        @foreach($itemLineupListHeader as $lineup)
                            <th>{{$itemLineupList[$lineup]}}</th>
                        @endforeach
                    </tr>

                    @foreach($itemNameList as $itemKey => $itemName)
                        <tr>
                            <th>{{$itemName}}</th>
                            @foreach($itemLineupListHeader as $lineup)
                                <td>
                                    @php
                                        $prop = 'count_' . $lineup . '_' . $itemKey;
                                        echo $summary->$prop . (strcmp($itemKey, 'sum_total') ?  __("admin.item_name.common.unit") :  __("admin.item_name.common.unit1"));
                                    @endphp
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>