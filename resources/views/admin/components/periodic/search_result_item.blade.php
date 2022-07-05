@inject('paymentList', 'App\Common\KeyValueLists\PaymentList')
@inject('periodicOrderStatusList', 'App\Common\KeyValueLists\PeriodicOrderStatusList')
@inject('periodicOrderStopFlagList', 'App\Common\KeyValueLists\PeriodicOrderStopFlagList')

{{-- 定期検索結果表示 --}}
<tr @if($order->duplication_warning_flag) style="background-color: #dd5555;" @endif>
    {{-- 定期ID/定期回 --}}
    <td>
        <a href="{{route("admin.periodic.edit",['id'=>$order->id])}}">{{$order->id}}</a><br>
        {{$order->periodic_count}}
    </td>

    {{-- 顧客ID/購入者名 --}}
    <td>
        {{$order->customer_id}}<br>
        {{$order->name01}} {{$order->name02}}
    </td>

    {{-- 商品名 --}}
    <td>
        @foreach(explode('<br>', $order->product_names) as $product_name)
            {{$product_name}}<br>
        @endforeach
    </td>

    {{-- 金額 --}}
    <td>
        {{$order->last_payment_total}}{{__("admin.item_name.common.wen")}}
    </td>

    {{-- 間隔 --}}
    <td>
        @if($order->periodic_interval_type_id===1)
            {{$order->interval_days}}{{__("admin.item_name.periodic.interval_days")}}
        @elseif($order->periodic_interval_type_id===2)
            {{$order->interval_month}}{{__("admin.item_name.periodic.interval_month")}}
            {{$order->interval_date_of_month}}{{__("admin.item_name.common.date_unit")}}
        @endif
    </td>

    {{-- 支払い方法 --}}
    <td class="d-none d-xl-table-cell">
        {{$paymentList[$order->payment_method_id]}}
    </td>

    {{-- SHOPメモ --}}
    <td>
        <a href="/admin/customer/shopmemo">
            @foreach(explode('<br>', $order->shop_memo_notes) as $note)
                {{$note}}<br>
            @endforeach
        </a>
    </td>

    {{-- 前回到着/次回到着 --}}
    <td>
        {{$order->last_delivery_date or "---- -- --"}}<br />
        {{$order->next_delivery_date}}
    </td>

    {{-- 稼働状況/停止フラグ --}}
    <td>
        {{$periodicOrderStatusList[$order->failed_flag]}}<br>
        {{$periodicOrderStopFlagList[$order->stop_flag]}}
    </td>

    <td>@if($order->duplication_warning_flag){{__("admin.item_name.periodic.duplicated")}}@endif</td>

    @if($deletable)
        <td>
            <button @click.prevent="delete_item({{$order->id}})"  class="btn btn-sm btn-danger"><i class="fa fa-remove"></i>&nbsp;
                {{__("admin.operation.delete")}}
            </button>
        </td>
    @endif
</tr>

