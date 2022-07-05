@inject('orderStatusList', 'App\Common\KeyValueLists\OrderStatusList')
@inject('paymentList', 'App\Common\KeyValueLists\PaymentList')

{{-- 受注検索結果１行の表示 --}}
@php
    if($order->color) {
        $color= $order->color;
    }elseif($order->display_purchase_warning_flag && $order->purchase_warning_flag){
        $color= "#DD5555";
    }
@endphp

<tr class="" @if(isset($color))style="background-color: {{$color}};"@endif>
    {{-- 受注日時 --}}
    <td class="d-none d-xl-table-cell">{{ date('Y/m/d H:i', strtotime($order->created_at)) }}</td>

    {{-- 注文ID --}}
    @if ($editable)
        @if($isShipping)
            <td><a href="{{route("admin.order.edit",['id'=>$order->id,'isShipping'=>$isShipping])}}">{{$order->id}}</a></td>
        @else
            <td><a href="{{route("admin.order.edit",['id'=>$order->id])}}">{{$order->id}}</a></td>
        @endif
    @else
        <td>{{$order['id']}}</td>
    @endif

    {{-- お名前 --}}
    <td>{{$order->name01}} {{$order->name02}}</td>

    {{-- 支払い方法 --}}
    <td class="d-none d-xl-table-cell">{{$paymentList[$order->payment_method_id]}}</td>

    {{-- 合計金額 --}}
    <td>{{$order->payment_total}}</td>

    {{-- 決済？？ --}}
    <td class="d-none d-xl-table-cell">{{$order->settlement_status_code}}</td>

    {{-- 到着予定日 --}}
    <td class="d-none d-xl-table-cell">{{ $order->estimated_arrival_date ? date('Y/m/d', strtotime($order->estimated_arrival_date)) : '' }}</td>

    {{-- 対応状況 --}}
    <td>
        <div class="col-form-label col-form-label-sm">
            <div class="form-check form-check-inline">
                @if($isShipping)
                    {{-- 配送管理:チェックボックスなし --}}

                @else
                    {{-- 受注検索:チェックボックスあり --}}
                    <input name="status_chk_list[{{$order->id}}]" class="form-check-input status_chk_list" type="checkbox" value="{{$order->id}}" v-model="status_chk_list">
                @endif

                {{-- 配送管理:変更不可 --}}
                <select name="status_id_list[{{$order->id}}]" class="form-control form-control-sm" @if($isShipping) disabled @endif>
                    <option></option>
                    @foreach($orderStatusList as $id => $name)
                        <option value="{{$id}}" @if($order->order_status_id === $id) selected @endif>{{$name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </td>

    {{-- 伝票番号 --}}
    <td>
        {{-- 配送管理:変更不可 --}}
        <input class="form-control form-control-sm" name="delivery_slip_num[{{$order->id}}]" type="text" value="{{$order->delivery_slip_num or ""}}" @if($isShipping) disabled @endif>
    </td>


    @if(!$isShipping)
        {{-- 受注検索 --}}
        {{-- 更新 --}}
        @if ($editable) <td><button @click.prevent="update_order({{$order->id}})" class="btn btn-sm btn-secondary"><i class="fa fa-refresh">&nbsp;{{__("admin.operation.update")}}</i></button></td> @endif

        {{-- 同梱 --}}
        <td>
            <div class="col-form-label col-form-label-sm text-center">
                <div class="form-check form-check-inline">
                    <input class="form-check-input bundle_chk_list" type="checkbox" name="bundle_chk_list[]" value="{{$order->id}}" v-model="bundle_chk_list">
                </div>
            </div>
        </td>

        {{-- メール --}}
        <td class="d-none d-xl-table-cell">
            <a href="{{route("admin.mail.send.order",['id'=>$order->id])}}"><button type="button" class="btn btn-sm btn-secondary"><i class="fa fa-envelope"></i>&nbsp;{{__("admin.operation.confirm")}}</button></a>
        </td>

        @if ($editable)
            <td class="d-none d-xl-table-cell">
                <a href="{{route("admin.order.edit",['id'=>$order->id])}}"><button type="button" class="btn btn-sm btn-secondary">{{__("admin.operation.edit")}}</button></a>
            </td>
        @endif

        {{-- 削除 --}}
        @if ($deletable)
            <td>
                <div class="col-form-label col-form-label-sm text-center">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input delete_chk_list" type="checkbox" form="delete_form" name="delete_chk_list[]" value="{{$order->id}}" v-model="delete_chk_list">
                    </div>
                </div>
            </td>
        @endif
    @else
        {{-- まとめ配送 --}}
        <td>
            {{$order->bundle_order_ids}}
        </td>

        {{-- 帳票出力 --}}
        <td>
            <div class="col-form-label col-form-label-sm">
                <div class="form-check form-check-inline">
                    <input class="form-check-input export_chk_list" type="checkbox" name="export_chk_list[]" value="{{$order->id}}" v-model="export_chk_list">
                    <button class="btn btn-sm btn-secondary" type="button" v-on:click="delivery_pdf({{$order->id}})">{{__("admin.operation.export")}}</button>
                </div>
            </div>


        </td>
    @endif
</tr>
