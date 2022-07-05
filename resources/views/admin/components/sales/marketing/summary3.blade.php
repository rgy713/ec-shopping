{{-- マーケティング集計.2 --}}
@inject('itemLineupList', 'App\Common\KeyValueLists\ItemLineupList')

@php
    $itemLineupListHeader = [1, 3, 4 ,5];

    $itemNameList=[
        "sum_2"=>"サンプル個数",
        "sum_3"=>"無料プレゼント個数",
    ];
@endphp


<div class="card">
    <div class="card-header">
        {{$head_name}}
        <div class="card-header-actions">
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            {{-- 総売上 --}}
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
                                        $prop = $itemKey . '_' . $lineup;
                                        echo $summary->$prop . __("admin.item_name.common.unit1");
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