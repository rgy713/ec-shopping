{{-- 「顧客情報」に表示する顧客の定期データ列定義 --}}
@inject('paymentList', 'App\Common\KeyValueLists\PaymentList')
@inject('periodicOrderStatusList', 'App\Common\KeyValueLists\PeriodicOrderStatusList')
@inject('periodicOrderStopFlagList', 'App\Common\KeyValueLists\PeriodicOrderStopFlagList')

{{--1行目--}}
<tr class="@if(!$periodicOrder->failed_flag || $periodicOrder->stop_flag) table-secondary @endif border border-0 periodic-item-first-line">
    {{-- 定期ID --}}
    <td class="pb-1">
        {{$periodicOrder->id}}
    </td>

    {{-- 商品名 --}}
    <td class="pb-1" rowspan="2">
        @foreach($periodicOrder->details as $detail)
            @if($detail->product->undelivered_summary_classification_id != 3)
                {{$detail->product_name}}<br/>
            @endif
        @endforeach
    </td>

    {{-- 合計金額 --}}
    <td class="pb-1">{{$periodicOrder->last_payment_total or 0}}円</td>

    {{-- 支払い方法 --}}
    <td class="pb-1">
        @if($periodicOrder->payment_method_id)
            <button type="button" class="btn btn-sm btn-block"
                    onclick="modal_periodic_payment_form.open(this);"
                    data-periodic_order_id="{{$periodicOrder->id}}"
                    data-updated_at="{{$periodicOrder->updated_at}}"
                    data-payment_method_id="{{$periodicOrder->payment_method_id}}"
                    data-settlement_card_id="{{$periodicOrder->settlement_card_id}}"
                    data-customer_id="{{$periodicOrder->customer_id}}"
                    data-payment_list="{{json_encode($paymentList)}}"
            >{{$paymentList[$periodicOrder->payment_method_id]}}</button>
        @endif
    </td>

    {{-- 前回到着日 --}}
    <td class="pb-1 text-sm-center">
        @if($periodicOrder->last_delivery_date){{$periodicOrder->last_delivery_date}}@else未着@endif<br />
    </td>

    {{-- 対応状況(正常/失敗) --}}
    <td class="pb-1">
        <button type="button" class="btn btn-sm btn-block"
                onclick="modal_periodic_failed_flag_form.open(this);"
                data-periodic_order_id="{{$periodicOrder->id}}"
                data-updated_at="{{$periodicOrder->updated_at}}"
                data-failed_flag="{{$periodicOrder->failed_flag}}"
        >{{$periodicOrderStatusList[$periodicOrder->failed_flag]}}</button>
    </td>
</tr>

{{--2行目--}}
<tr class="@if(!$periodicOrder->failed_flag || $periodicOrder->stop_flag) table-secondary @endif border border-0 periodic-item-second-line">
    {{-- 定期回 --}}
    <td class="border border-0 pt-0">
        {{$periodicOrder->periodic_count}}回
    </td>

    {{-- 空欄 --}}
    <td class="border border-0 pt-0 mt-0"></td>

    {{-- 定期間隔 --}}
    <td class="border border-0 pt-0 mt-0">
        @switch($periodicOrder->periodic_interval_type_id)
            @case(1)
            <button type="button" class="btn btn-sm btn-block"
                    onclick="modal_periodic_interval_form.open(this);"
                    data-periodic_interval_type_id="{{$periodicOrder->periodic_interval_type_id}}"
                    data-interval_days="{{$periodicOrder->interval_days}}"
                    data-periodic_order_id="{{$periodicOrder->id}}"
                    data-updated_at="{{$periodicOrder->updated_at}}"
            >{{$periodicOrder->interval_days}}日間</button>
            @break

            @case(2)
            <button type="button" class="btn btn-sm btn-block"
                    onclick="modal_periodic_interval_form.open(this);"
                    data-periodic_interval_type_id="{{$periodicOrder->periodic_interval_type_id}}"
                    data-interval_month="{{$periodicOrder->interval_month}}"
                    data-interval_date_of_month="{{$periodicOrder->interval_date_of_month}}"
                    data-periodic_order_id="{{$periodicOrder->id}}"
                    data-updated_at="{{$periodicOrder->updated_at}}"
            >{{$periodicOrder->interval_month}}ヶ月毎{{$periodicOrder->interval_date_of_month}}日</button>
            @break

            @default
        @endswitch
    </td>

    {{-- 次回到着 --}}
    <td class="border border-0 pt-0 mt-0">
        <button type="button" class="btn btn-sm btn-block"
                onclick="modal_periodic_next_delivery_date_form.open(this);"
                data-next_delivery_date="{{$periodicOrder->next_delivery_date}}"
                data-periodic_order_id="{{$periodicOrder->id}}"
                data-updated_at="{{$periodicOrder->updated_at}}"
        >{{$periodicOrder->next_delivery_date}}</button>
    </td>

    {{-- 稼働状況 --}}
    <td class="border border-0 pt-0 mt-0">
        <button type="button" class="btn btn-sm btn-block"
                onclick="modal_periodic_stop_flag_form.open(this);"
                data-stop_flag="{{$periodicOrder->stop_flag}}"
                data-periodic_order_id="{{$periodicOrder->id}}"
                data-updated_at="{{$periodicOrder->updated_at}}"
        >{{$periodicOrderStopFlagList[$periodicOrder->stop_flag]}}</button>
    </td>
</tr>

