@inject('deliveryList', 'App\Common\KeyValueLists\DeliveryList')
@inject('deliveryRequestDateList', 'App\Common\KeyValueLists\DeliveryRequestDateList')
@inject('paymentList', 'App\Common\KeyValueLists\PaymentList')

{{-- 受注の配送、支払い情報：受注、定期で共通 --}}
<div class="card">
    <div class="card-header">
        {{__("admin.page_header_name.order_delivery")}}
        <div class="card-header-actions">
            <button type="button" class="btn btn-sm btn-primary" onclick="auto_input_delivery();"><i class="fa fa-address-book"></i>&nbsp;{{__('admin.item_name.order.delivery_auto_input')}}</button>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="row">
                    {{-- お支払い方法 --}}
                    <div class="col-12">
                        <div class="row form-group">
                            <label class="col-4 col-form-label col-form-label-sm" for="text-input">{{__("admin.item_name.order.payment_method")}}</label>
                            <div class="col-8">
                                <select name="payment_method_id" class="form-control form-control-sm" required
                                        data-value="@if(!is_null(old('payment_method_id'))){{old('payment_method_id')}}@elseif(isset($order) && isset($order['payment_method_id'])){{$order['payment_method_id']}}@endif"
                                        data-fee="{{ $paymentList->getFeeValueList() }}">
                                    <option value=""></option>
                                    @if($isPeriodic===true)
                                        @foreach($paymentList as $id => $name)
                                            <option value="{{$id}}" @if(!is_null(old('payment_method_id')) && old('payment_method_id') == $id) selected @elseif(isset($order) && isset($order['payment_method_id']) && $order['payment_method_id'] == $id) selected  @endif>{{$name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    @if($isPeriodic===true)
                        <input id="settlement_card_id_input" name="settlement_card_id" type="hidden" value="@if(!is_null(old('settlement_card_id'))){{old('settlement_card_id')}}@elseif(isset($order) && isset($order['settlement_card_id'])){{$order['settlement_card_id']}}@endif">
                    @else
                        <input id="settlement_id_input" name="settlement_id" type="hidden" value="@if(!is_null(old('settlement_id'))){{old('settlement_id')}}@elseif(isset($order) && isset($order['settlement_id'])){{$order['settlement_id']}}@endif">
                    @endif
                    {{-- 決済 --}}
                    @if($mode==="create" && $isPeriodic===false)

                    @else
                        <div class="col-12">
                            <div class="row form-group">
                                <label class="col-4 col-form-label col-form-label-sm" for="text-input">{{__('admin.item_name.order.payment')}}</label>
                                <div class="col-8">
                                    <button type="button" id="card_payment_button" class="btn btn-sm btn-primary btn-block" onclick="modal_order_credit_form.open(this);">{{__('admin.item_name.order.card_payment')}}</button>
                                </div>
                            </div>
                        </div>

                        <input id="settlement_masked_card_number_input" name="settlement_masked_card_number" type="hidden" value="@if(!is_null(old('settlement_masked_card_number'))){{old('settlement_masked_card_number')}}@elseif(isset($order) && isset($order['settlement_masked_card_number'])){{$order['settlement_masked_card_number']}}@endif">
                        <div class="col-12">
                            <div class="row form-group">
                                <label class="col-4 col-form-label col-form-label-sm" for="text-input">{{__('admin.item_name.settlement.card_number')}}</label>
                                <div class="col-8">
                                    <input id="settlement_masked_card_number" readonly type="text" class="form-control form-control-sm" value="">
                                </div>
                            </div>
                        </div>

                    @endif

                    {{-- 配送業者 --}}
                    <div class="col-12">
                        <div class="row form-group">
                            <label class="col-4 col-form-label col-form-label-sm" for="text-input">{{__("admin.item_name.order.delivery_provider")}}</label>

                            <div class="col-8">
                                <select name="order_delivery_id" class="form-control form-control-sm" required>
                                    <option value=""></option>
                                    @if($mode==="create")
                                        {{-- 新規登録時 --}}
                                        @foreach($deliveryList->getUserVisibleDeliveryList() as $id => $name)
                                            <option value="{{$id}}" @if(!is_null(old('order_delivery_id')) && old('order_delivery_id') == $id) selected @elseif(isset($order) && isset($order['order_delivery_id']) && $order['order_delivery_id'] == $id) selected  @endif>{{$name}}</option>
                                        @endforeach

                                    @elseif($mode==="edit")
                                        {{-- 編集時 --}}
                                        @foreach($deliveryList as $id => $name)
                                            <option value="{{$id}}" @if(!is_null(old('order_delivery_id')) && old('order_delivery_id') == $id) selected @elseif(isset($order) && isset($order['order_delivery_id']) && $order['order_delivery_id'] == $id) selected  @endif>{{$name}}</option>
                                        @endforeach

                                    @endif

                                </select>

                            </div>
                        </div>
                    </div>

                    {{-- お届け希望日 --}}
                    @if($isPeriodic===false)
                        <div class="col-12">
                            <div class="row form-group">
                                <label class="col-4 col-form-label col-form-label-sm" for="text-input">{{__("admin.item_name.shipping.requested_delivery_date")}}</label>

                                <div class="col-8">

                                    @if($mode==="create")
                                        {{-- 新規登録時 --}}
                                        <select name="shippings_requested_delivery_date" class="form-control form-control-sm"
                                                data-value="@if(!is_null(old('shippings_requested_delivery_date'))){{old('shippings_requested_delivery_date')}}@elseif(isset($order) && isset($order['shippings_requested_delivery_date'])){{$order['shippings_requested_delivery_date']}}@endif">
                                            <option value=""></option>
                                        </select>
                                    @elseif($mode==="edit")
                                        {{-- 編集時 --}}
                                        <input name="shippings_requested_delivery_date" class="form-control form-control-sm" type="date"
                                               value="@if(!is_null(old('shippings_requested_delivery_date'))){{old('shippings_requested_delivery_date')}}@elseif(isset($order) && isset($order['shippings_requested_delivery_date'])){{$order['shippings_requested_delivery_date']}}@endif">
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- お届け希望時間 --}}
                    <div class="col-12">
                        <div class="row form-group">
                            <label class="col-4 col-form-label col-form-label-sm" for="text-input">{{__("admin.item_name.shipping.delivery_expected_time")}}</label>


                            <div class="col-8">
                                @if($isPeriodic===true)
                                    {{-- 定期の場合、お届けの時刻そのものを保存する --}}
                                    <div class="input-group-prepend">
                                        <input name="shippings_delivery_time_id" type="time" class="form-control form-control-sm"
                                            value="@if(!is_null(old('shippings_delivery_time_id'))){{old('shippings_delivery_time_id')}}@elseif(isset($order) && isset($order['shippings_delivery_time_id'])){{$order['shippings_delivery_time_id']}}@endif">
                                        <span class="input-group-text form-control-sm">{{__("admin.item_name.common.moment")}}</span>
                                    </div>

                                @else
                                    {{-- 受注の場合、お届けの時刻を示すidを保存する --}}
                                    <select name="shippings_delivery_time_id" class="form-control form-control-sm"
                                            data-value="@if(!is_null(old('shippings_delivery_time_id'))){{old('shippings_delivery_time_id')}}@elseif(isset($order) && isset($order['shippings_delivery_time_id'])){{$order['shippings_delivery_time_id']}}@endif">
                                        <option value=""></option>
                                    </select>
                                @endif
                            </div>


                        </div>
                    </div>


                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="row">
                    @include('admin.components.common.address_form',['prefix'=>'delivery','obj'=>isset($order) ? $order : null])
                </div>
            </div>
        </div>

    </div>
</div>

@include('admin.components.order.modal_order_credit_form')


@push('content_js')
    <script>
        function auto_input_delivery(){
            $('input[name=delivery_name01]').val($('input[name=customer_name01]').val());
            $('input[name=delivery_name02]').val($('input[name=customer_name02]').val());
            $('input[name=delivery_kana01]').val($('input[name=customer_kana01]').val());
            $('input[name=delivery_kana02]').val($('input[name=customer_kana02]').val());
            $('input[name=delivery_phone_number01]').val($('input[name=customer_phone_number01]').val());
            $('input[name=delivery_phone_number02]').val($('input[name=customer_phone_number02]').val());
            $('input[name=delivery_zipcode]').val($('input[name=customer_zipcode]').val());
            $('select[name=delivery_prefecture]').val($('select[name=customer_prefecture]').val());
            $('input[name=delivery_address1]').val($('input[name=customer_address1]').val());
            $('input[name=delivery_address2]').val($('input[name=customer_address2]').val());
            $('input[name=delivery_address3]').val($('input[name=customer_address3]').val());
        }

        function get_delivery_fee(delivery_id, prefecture_id, autoSummary) {
            if (delivery_id && prefecture_id) {
                axios.get('/api/value/deliveryFee/' + delivery_id + '/' + prefecture_id).then(response => {
                    if (response.data) {
                        var delivery_fee = response.data.fee;

                        var input_order_delivery_fee = $('input[name=order_delivery_fee]');
                        if (!autoSummary && !input_order_delivery_fee.prop('readonly')) {
                            input_order_delivery_fee.val(delivery_fee);
                        }

                        if (!autoSummary) {
                            order_item_vue.get_summary();
                        }
                    }
                })
            }
        }

        function deliveryRequestDateListWithLeadTime(order_delivery_id, delivery_prefecture) {
            var shippings_requested_delivery_date = $('select[name=shippings_requested_delivery_date]');
            shippings_requested_delivery_date.empty();
            if (order_delivery_id && delivery_prefecture) {
                axios.get('/api/lists/deliveryRequestDateListWithLeadTime/' + order_delivery_id + '/' + delivery_prefecture).then(response => {
                    var deliveryRequestDateListWithLeadTime = response.data;
                    shippings_requested_delivery_date.append('<option value=""></option>');
                    var old_shippings_requested_delivery_date = $('select[name=shippings_requested_delivery_date]').data('value');
                    for (var deliveryRequestDate in deliveryRequestDateListWithLeadTime) {
                        shippings_requested_delivery_date.append('<option value="'+ deliveryRequestDate + '"' + (old_shippings_requested_delivery_date == deliveryRequestDate ? ' selected ' : '') + '>' + deliveryRequestDateListWithLeadTime[deliveryRequestDate] + '</option>')
                    }
                })
            }
        }

        $('select[name=order_status_id]').change(function () {
            orderStatusSelected(this.value)
        })

        function orderStatusSelected(new_order_status_id) {
            @if($isPeriodic == false)
                if (new_order_status_id) {
                    var payment_method_id = $('select[name=payment_method_id]');
                    payment_method_id.empty();

                    axios.get('/api/lists/paymentList/' + new_order_status_id).then(response => {
                        var paymentList = response.data;

                        payment_method_id.append('<option value=""></option>');
                        var old_payment_method_id = payment_method_id.data('value');
                        paymentList.forEach(function (payment_method) {
                            payment_method_id.append('<option value="'+ payment_method.id + '"' + (old_payment_method_id == payment_method.id ? ' selected ' : '') + '>' + payment_method.name + '</option>')
                        })
                    })
                }
            @endif
        }

        var settlement_id = $('input[name=settlement_id]').val();
        var settlement_masked_card_number = $('input[name=settlement_masked_card_number]').val();
        var select_payment_method_id = $('select[name=payment_method_id]').val();

        $('select[name=payment_method_id]').change(function () {
            if (this.value) {
                var payment_fee = $('select[name=payment_method_id]').data('fee');
                var input_order_payment_method_fee = $('input[name=order_payment_method_fee]');
                if (!input_order_payment_method_fee.prop('readonly')) {
                    input_order_payment_method_fee.val(payment_fee[this.value]);
                }

                order_item_vue.get_summary();
            }

            @if($isPeriodic == false)
                $('#card_payment_button').prop('disabled', this.value != 5 || !settlement_id )
                $('#settlement_masked_card_number').val(this.value == 5 && settlement_id ? settlement_masked_card_number : '')
            @else
                $('#card_payment_button').prop('disabled', this.value != 5 || !$('input[name=customer_id]').val() )
                $('#settlement_masked_card_number').val(settlement_masked_card_number)
            @endif

        })

        @if($isPeriodic == false)
            $('#card_payment_button').prop('disabled', select_payment_method_id != 5 || !settlement_id )
            $('#settlement_masked_card_number').val(select_payment_method_id == 5 && settlement_id ? settlement_masked_card_number : '')
        @else
            $('#card_payment_button').prop('disabled', select_payment_method_id != 5 || !$('input[name=customer_id]').val() )
            $('#settlement_masked_card_number').val(settlement_masked_card_number)
        @endif

        $('select[name=order_delivery_id]').change(function () {
            deliverySelected(this.value);
        })

        function deliverySelected(new_order_delivery_id, autoSummary) {
            if (!new_order_delivery_id) return;

            @if($isPeriodic == false)
                var select_shippings_delivery_time_id = $('select[name=shippings_delivery_time_id]');
                select_shippings_delivery_time_id.empty();

                if (new_order_delivery_id) {
                    axios.get('/api/lists/deliveryTimeList/' + new_order_delivery_id).then(response => {
                        var deliveryTimeList = response.data;

                        select_shippings_delivery_time_id.append('<option value=""></option>');
                        var old_shippings_delivery_time_id = $('select[name=shippings_delivery_time_id]').data('value');
                        deliveryTimeList.forEach(function (deliveryTime) {
                            select_shippings_delivery_time_id.append('<option value="'+ deliveryTime.id + '"' + (old_shippings_delivery_time_id == deliveryTime.id ? ' selected ' : '') + '>' + deliveryTime.delivery_time + '</option>')
                        })
                    })
                }
            @endif

            var delivery_prefecture_id = $('select[name=delivery_prefecture]').val();

            deliveryRequestDateListWithLeadTime(new_order_delivery_id, delivery_prefecture_id);
            get_delivery_fee(new_order_delivery_id, delivery_prefecture_id, autoSummary);
        }

        $('select[name=delivery_prefecture]').change(function () {
            if (this.value) {
                var new_delivery_prefecture = this.value;

                var order_delivery_id = $('select[name=order_delivery_id]').val();

                deliveryRequestDateListWithLeadTime(order_delivery_id, new_delivery_prefecture);

                get_delivery_fee(order_delivery_id, new_delivery_prefecture);
            }
        })

        orderStatusSelected($('select[name=order_status_id]').val())
        deliverySelected($('select[name=order_delivery_id]').val(), true);

    </script>
@endpush