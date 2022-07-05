@php
    $total = $summary[0]['customer_count'];
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
                        <th>{{__("admin.item_name.customer.buy_times")}}</th>
                        <th>{{__("admin.item_name.common.customer_count")}}</th>
                        <th>{{__("admin.item_name.common.ratio")}}</th>
                    </tr>

                    @foreach($summary as $key=>$item)
                        @if ($key == 0)
                            <tr>
                                <th>{{__("admin.item_name.sales.customer_sum")}}</th>
                                <td>{{$total}}{{__("admin.item_name.common.man_unit1")}}</td>
                                <td>100%</td>
                            </tr>
                        @else
                            <tr>
                                <th>{{$item->counts}}{{__("admin.item_name.sales.count_unit")}}@if ($item->counts == 10){{__("admin.item_name.common.greater")}}@elseif ($item->counts == 0){{__("admin.item_name.sales.first_customer")}}@endif</th>
                                <td>{{$item->customer_count > 0 ? $item->customer_count : 0}}{{__("admin.item_name.common.man_unit1")}}</td>
                                <td>{{$item->customer_count > 0 && $total > 0 ? round($item->customer_count / $total * 100, 2) : 0}}％</td>
                            </tr>
                        @endif
                    @endforeach

                </table>
            </div>
        </div>
    </div>
</div>