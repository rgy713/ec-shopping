@inject('deliveryList', 'App\Common\KeyValueLists\DeliveryList')
@inject('deliveryRequestDateList', 'App\Common\KeyValueLists\DeliveryRequestDateList')
@inject('paymentList', 'App\Common\KeyValueLists\PaymentList')

{{-- 受注の配送、支払い情報：受注、定期で共通 --}}
<div class="card">
    <div class="card-header">
        {{__("admin.page_header_name.order_delivery")}}
        <div class="card-header-actions">

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
                                <input name="payment_method_id" type="hidden" value="@if(isset($order) && isset($order['payment_method_id'])){{$order['payment_method_id']}}@endif">
                                <input type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if(isset($order) && isset($order['payment_method_id'])){{$paymentList[$order['payment_method_id']]}}@endif">
                            </div>
                        </div>
                    </div>

                    @if($isPeriodic===true)
                        <input id="settlement_card_id_input" name="settlement_card_id" type="hidden" value="@if(!is_null(old('settlement_card_id'))){{old('settlement_card_id')}}@elseif(isset($order) && isset($order['settlement_card_id'])){{$order['settlement_card_id']}}@endif">
                    @else
                        <input id="settlement_id_input" name="settlement_id" type="hidden" value="@if(!is_null(old('settlement_id'))){{old('settlement_id')}}@elseif(isset($order) && isset($order['settlement_id'])){{$order['settlement_id']}}@endif">
                    @endif

                    {{-- 決済 --}}
                    @if($isPeriodic===false)

                    @else

                        <div class="col-12">
                            <div class="row form-group">
                                <label class="col-4 col-form-label col-form-label-sm" for="text-input">{{__('admin.item_name.settlement.card_number')}}</label>
                                <div class="col-8">
                                    <input name="settlement_masked_card_number" type="text" readonly class="form-control-plaintext form-control-sm"
                                           value="@if(!is_null(old('settlement_masked_card_number'))){{old('settlement_masked_card_number')}}@elseif(isset($order) && isset($order['settlement_masked_card_number'])){{$order['settlement_masked_card_number']}}@endif">
                                </div>
                            </div>
                        </div>

                    @endif

                    {{-- 配送業者 --}}
                    <div class="col-12">
                        <div class="row form-group">
                            <label class="col-4 col-form-label col-form-label-sm" for="text-input">{{__("admin.item_name.order.delivery_provider")}}</label>

                            <div class="col-8">
                                <input name="order_delivery_id" type="hidden" value="@if(isset($order) && isset($order['order_delivery_id'])){{$order['order_delivery_id']}}@endif">
                                <input type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if(isset($order) && isset($order['order_delivery_id'])){{$deliveryList[$order['order_delivery_id']]}}@endif">
                            </div>
                        </div>
                    </div>

                    {{-- お届け希望日 --}}
                    @if($isPeriodic===false)
                        <div class="col-12">
                            <div class="row form-group">
                                <label class="col-4 col-form-label col-form-label-sm" for="text-input">{{__("admin.item_name.shipping.requested_delivery_date")}}</label>

                                <div class="col-8">
                                    <input name="shippings_requested_delivery_date" type="hidden" value="@if(isset($order) && isset($order['shippings_requested_delivery_date'])){{$order['shippings_requested_delivery_date']}}@endif">
                                    @if (isset($order) && isset($order['order_id']))
                                        <input type="text" readonly class="form-control-plaintext form-control-sm"
                                               value="@if(isset($order) && isset($order['shippings_requested_delivery_date'])){{$order['shippings_requested_delivery_date']}}@endif">
                                    @else
                                        <input type="text" readonly class="form-control-plaintext form-control-sm"
                                               value="@if(isset($order) && isset($order['shippings_requested_delivery_date']) && isset($order['delivery_prefecture']) && isset($order['order_delivery_id'])){{$deliveryRequestDateList->getWithLeadTime($order['delivery_prefecture'], isset($order['order_delivery_id']))[$order['shippings_requested_delivery_date']]}}@endif">
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
                                        <input name="shippings_delivery_time_id" type="text" readonly class="form-control-plaintext form-control-sm"
                                               value="@if(isset($order) && isset($order['shippings_delivery_time_id'])){{$order['shippings_delivery_time_id']}}@endif">
                                        <span class="input-group-text form-control-sm">{{__("admin.item_name.common.moment")}}</span>
                                    </div>

                                @else
                                    {{-- 受注の場合、お届けの時刻を示すidを保存する --}}
                                    <input name="shippings_delivery_time_id" type="hidden" value="@if(isset($order) && isset($order['shippings_delivery_time_id'])){{$order['shippings_delivery_time_id']}}@endif">
                                    <input type="text" class="form-control-plaintext form-control-sm"
                                           value="@if(isset($order) && isset($order['shippings_delivery_time_id']) && isset($order['order_delivery_id'])){{($deliveryList->getDeliveryTimeList($order['order_delivery_id']))[$order['shippings_delivery_time_id']]}}@endif">
                                @endif
                            </div>


                        </div>
                    </div>


                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="row">
                    @include('admin.components.common.address_info',['prefix'=>'delivery'])
                </div>
            </div>
        </div>

    </div>
</div>