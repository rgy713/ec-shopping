@inject('orderStatusList', 'App\Common\KeyValueLists\OrderStatusList')
@inject('purchaseRouteList', 'App\Common\KeyValueLists\PurchaseRouteList')


{{-- 受注情報編集フォーム：受注基本情報 --}}
<div class="card">
    <div class="card-header">
        {{__('admin.page_header_name.order_create')}}
        <div class="card-header-actions">

        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <div class="row">
                    {{-- 注文番号 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.id')}}</label>
                            <div class="col-8 col-lg-10">
                                <input name="order_id" type="text" readonly class="form-control-plaintext form-control-sm form-control-plaintext"
                                       value="@if(isset($order) && isset($order['order_id'])){{$order['order_id']}}@endif">
                            </div>
                        </div>
                    </div>

                    {{-- 対応状況 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.status')}}</label>
                            <div class="col-8 col-lg-10">
                                <input name="order_status_id" type="hidden" value="@if(isset($order) && isset($order['order_status_id'])){{$order['order_status_id']}}@endif">
                                <input type="text" readonly class="form-control-plaintext form-control-sm form-control-plaintext"
                                       value="@if(isset($order) && isset($order['order_status_id'])){{$orderStatusList->getPermittedList(0)[$order['order_status_id']]}}@endif">
                            </div>
                        </div>
                    </div>
                    {{-- 購入方法 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__("admin.item_name.order.how_to_buy")}}</label>

                            <div class="col-8 col-lg-10">
                                <input name="purchase_route_id" type="hidden" value="@if(isset($order) && isset($order['purchase_route_id'])){{$order['purchase_route_id']}}@endif">
                                <input type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if(isset($order) && isset($order['purchase_route_id'])){{$purchaseRouteList[$order['purchase_route_id']]}}@endif">
                            </div>
                        </div>
                    </div>
                    {{-- エラー表示 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.error_flag')}}</label>

                            <div class="col-8 col-lg-10">
                                <input name="display_purchase_warning_flag" type="hidden" value="@if(isset($order) && isset($order['display_purchase_warning_flag'])){{$order['display_purchase_warning_flag']}}@endif">
                                <input type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if(isset($order) && isset($order['display_purchase_warning_flag']) && $order['display_purchase_warning_flag']){{__('admin.item_name.common.do')}}@else{{__('admin.item_name.common.dont')}}@endif">
                            </div>
                        </div>
                    </div>


                    {{-- 備考 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__("admin.item_name.common.remark")}}</label>

                            <div class="col-8 col-lg-10">
                                <input name="message_from_customer" readonly type="text" class="form-control-plaintext form-control-sm"
                                       value="@if(isset($order) && isset($order['message_from_customer'])){{$order['message_from_customer']}}@endif">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="row">
                    {{-- 受注日時 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-sm-4 col-form-label col-form-label-sm">{{__('admin.item_name.order.create_date')}}</label>
                            <div class="col-sm-8" >
                                <input type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if (isset($order) && isset($order['updated_at'])) {{ date('Y/m/d', strtotime($order['updated_at']))}}@endif">
                            </div>
                        </div>
                    </div>

                    {{-- 売上計上日時 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-sm-4 col-form-label col-form-label-sm">{{__('admin.item_name.order.sales_timestamp')}}</label>
                            <div class="col-sm-8" >
                                <input type="text" readonly class="form-control-plaintext form-control-sm" tabindex="-1"
                                       value="@if (isset($order) && isset($order['sales_timestamp'])){{ date('Y/m/d', strtotime($order['sales_timestamp']))}}@endif">
                            </div>
                        </div>
                    </div>

                    {{-- 発送予定日 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-sm-4 col-form-label col-form-label-sm">{{__('admin.item_name.shipping.scheduled_shipping_date')}}</label>
                            <div class="col-sm-8" >
                                <input type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if (isset($order) && isset($order['scheduled_shipping_date'])){{ date('Y/m/d', strtotime($order['scheduled_shipping_date']))}}@endif">
                            </div>
                        </div>
                    </div>
                    {{-- 発送日 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-sm-4 col-form-label col-form-label-sm">{{__('admin.item_name.shipping.shipped_timestamp')}}</label>
                            <div class="col-sm-8" >
                                <input type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if (isset($order) && isset($order['shipped_timestamp'])){{ date('Y/m/d', strtotime($order['shipped_timestamp']))}}@endif">
                            </div>
                        </div>
                    </div>
                    {{-- 到着予定日 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-sm-4 col-form-label col-form-label-sm">{{__('admin.item_name.shipping.estimated_arrival_date')}}</label>
                            <div class="col-sm-8" >
                                <input type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if (isset($order) && isset($order['estimated_arrival_date'])){{ date('Y/m/d', strtotime($order['estimated_arrival_date']))}}@endif">
                            </div>
                        </div>
                    </div>
                    {{-- キャンセル日 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-sm-4 col-form-label col-form-label-sm">{{__('admin.item_name.order.canceled_timestamp')}}</label>
                            <div class="col-sm-8" >
                                <input type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if (isset($order) && isset($order['canceled_timestamp'])){{ date('Y/m/d', strtotime($order['canceled_timestamp']))}}@endif">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>