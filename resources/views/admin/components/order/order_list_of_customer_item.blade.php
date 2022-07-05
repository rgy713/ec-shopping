@inject('orderStatusList', 'App\Common\KeyValueLists\OrderStatusList')
@inject('paymentList', 'App\Common\KeyValueLists\PaymentList')

{{-- TODO:「購入方法」については、商品分類の「販売経路」と同じ選択肢で良いのか仕様整理が必要 --}}
@inject('purchaseRouteList', 'App\Common\KeyValueLists\PurchaseRouteList')

{{--  --}}
@php
    if($order->status->color)
    {
        $color= $order->status->color;
    }elseif($order->display_purchase_warning_flag && $order->purchase_warning_flag){
        $color= "#DD5555";
    }
@endphp
<tr @if(isset($color))style="background-color: {{$color}};"@endif>
    <td><a target="__blank" onclick="PopupWindow('{{route("admin.order.popup.edit",['id'=>$order->id])}}')">{{$order->id}}</a><br>
        @datetime($order->created_at)
    </td>
    <td>
        @foreach($order->details as $detail)
            @if($detail->product->undelivered_summary_classification_id != 3)
                {{$detail->product_name}}<br/>
            @endif
        @endforeach
    </td>
    <td>{{$order->payment_total}}円</td>
    <td>
        {{$order->payment_method_name}}<br />
        {{$orderStatusList[$order->order_status_id] or ""}}
    </td>

    <td>
        @if(isset($order->shipping->shipped_timestamp)){{$order->shipping->shipped_timestamp}}@else 未定 @endif<br />
        @if(isset($order->shipping->estimated_arrival_date)){{$order->shipping->estimated_arrival_date}}@else 未定 @endif<br />
        {{ $order->shipping->delivery_slip_num or "未定"}}
    </td>

    <td></td>
    <td>@if($order->purchase_route_id){{$purchaseRouteList[$order->purchase_route_id]}}@endif</td>
</tr>

