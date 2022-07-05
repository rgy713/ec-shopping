@inject('periodicOrderStatusList', 'App\Common\KeyValueLists\PeriodicOrderStatusList')
@inject('purchaseRouteList', 'App\Common\KeyValueLists\PurchaseRouteList')
@inject('periodicOrderStopFlagList', 'App\Common\KeyValueLists\PeriodicOrderStopFlagList')

{{-- 定期基本情報 --}}
<div class="card">
    <div class="card-header">
        {{__('admin.page_header_name.periodic_information')}}
        <div class="card-header-actions">
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    {{-- 定期番号 --}}
                    <div class="col-6 col-lg-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.id')}}</label>
                            <div class="col-8 col-lg-10">
                                <input name="order_id" type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if(isset($order) && isset($order['order_id'])){{$order['order_id']}}@endif">
                            </div>
                        </div>
                    </div>
                    {{-- 定期回数 --}}
                    <div class="col-6 col-lg-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.count')}}</label>
                            <div class="col-8 col-lg-10">
                                <input name="periodic_count" type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if(isset($order) && isset($order['periodic_count'])){{$order['periodic_count']}}@endif">
                            </div>
                        </div>
                    </div>
                    {{-- 受注日時 --}}
                    <div class="col-6 col-lg-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.create_date')}}</label>
                            <div class="col-8 col-lg-10" >
                                <input type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if (isset($order) && isset($order['updated_at'])){{ date('Y/m/d', strtotime($order['updated_at']))}}@endif">
                            </div>
                        </div>
                    </div>


                    {{-- 前回到着日 --}}
                    <div class="col-6 col-lg-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.prev_create_date')}}</label>
                            <div class="col-8 col-lg-10">
                                <input type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if (isset($order) && isset($order['last_delivery_date'])){{ date('Y/m/d', strtotime($order['last_delivery_date']))}}@endif">
                            </div>
                        </div>
                    </div>

                    <input name="periodic_interval_type_id" type="hidden"
                           value="@if(isset($order) && isset($order['periodic_interval_type_id'])){{$order['periodic_interval_type_id']}}@endif">

                    {{-- 定期間隔 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-2 col-lg-1 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.interval')}}</label>
                            @if (isset($order) && isset($order['periodic_interval_type_id']) && $order['periodic_interval_type_id'] == 1)
                                <div class="col-4 col-lg-3">
                                    <div class="input-group-prepend">
                                        <input name="interval_days" type="text" readonly class="form-control-plaintext form-control-sm form-control-plaintext"
                                               value="@if(isset($order) && isset($order['interval_days'])){{$order['interval_days']}}@endif">
                                        <span class="input-group-text form-control-sm">{{__('admin.item_name.common.date_unit')}}</span>
                                    </div>
                                </div>
                            @elseif (isset($order) && isset($order['periodic_interval_type_id']) && $order['periodic_interval_type_id'] == 2)
                                <div class="col-3 col-lg-3 pr-0">
                                    <div class="input-group-prepend">
                                        <input name="interval_month" type="text" readonly class="form-control-plaintext form-control-sm form-control-plaintext"
                                               value="@if(isset($order) && isset($order['interval_month'])){{$order['interval_month']}}@endif">
                                        <span class="input-group-text form-control-sm">{{__('admin.item_name.periodic.interval_month')}}</span>
                                    </div>
                                </div>
                                <div class="col-3 col-lg-3 pl-0">
                                    <div class="input-group-prepend">
                                        <input name="interval_date_of_month" type="text" readonly class="form-control-plaintext form-control-sm form-control-plaintext"
                                               value="@if(isset($order) && isset($order['interval_date_of_month'])){{$order['interval_date_of_month']}}@endif">
                                        <span class="input-group-text form-control-sm">{{__('admin.item_name.common.date_unit')}}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 次回到着日 --}}
                    <div class="col-6 col-lg-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.next_create_date')}}</label>
                            <div class="col-8 col-lg-10">
                                <input name="next_delivery_date" type="text" readonly class="form-control-plaintext form-control-sm form-control-plaintext"
                                       value="@if(isset($order) && isset($order['next_delivery_date'])){{$order['next_delivery_date']}}@endif">
                            </div>
                        </div>
                    </div>

                    {{-- 対応状況 --}}
                    <div class="col-6 col-lg-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.status')}}</label>
                            <div class="col-8 col-lg-10">
                                <input name="periodic_failed_flag" type="hidden"
                                       value="@if(isset($order) && isset($order['periodic_failed_flag'])){{$order['periodic_failed_flag']}}@endif">
                                <input type="text" readonly class="form-control-plaintext form-control-sm form-control-plaintext"
                                       value="@if(isset($order) && isset($order['periodic_failed_flag'])){{$periodicOrderStatusList[$order['periodic_failed_flag']]}}@endif">
                            </div>
                        </div>
                    </div>

                    {{-- 稼働状況 --}}
                    <div class="col-6 col-lg-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.stop_flag')}}</label>
                            <div class="col-8 col-lg-10">
                                <input name="periodic_stop_flag" type="hidden"
                                       value="@if(isset($order) && isset($order['periodic_stop_flag'])){{$order['periodic_stop_flag']}}@endif">
                                <input type="text" readonly class="form-control-plaintext form-control-sm form-control-plaintext"
                                       value="@if(isset($order) && isset($order['periodic_stop_flag'])){{$periodicOrderStopFlagList[$order['periodic_stop_flag']]}}@endif">
                            </div>
                        </div>
                    </div>

                    {{-- 購入方法 --}}
                    <div class="col-6 col-lg-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__("admin.item_name.order.how_to_buy")}}</label>

                            <div class="col-8 col-lg-10">
                                <input name="purchase_route_id" type="hidden"
                                       value="@if(isset($order) && isset($order['purchase_route_id'])){{$order['purchase_route_id']}}@endif">
                                <input type="text" readonly class="form-control-plaintext form-control-sm form-control-plaintext"
                                       value="@if(isset($order) && isset($order['purchase_route_id'])){{$purchaseRouteList[$order['purchase_route_id']]}}@endif">
                            </div>
                        </div>
                    </div>

                    {{-- 備考 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-2 col-lg-1 col-form-label col-form-label-sm">{{__("admin.item_name.common.remark")}}</label>
                            <div class="col-10 col-lg-11">
                                <input name="message_from_customer" type="text" readonly class="form-control-plaintext form-control-sm form-control-plaintext"
                                       value="@if(isset($order) && isset($order['message_from_customer'])){{$order['message_from_customer']}}@endif">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>