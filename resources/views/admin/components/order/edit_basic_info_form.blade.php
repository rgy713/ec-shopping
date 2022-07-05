@inject('orderStatusList', 'App\Common\KeyValueLists\OrderStatusList')
@inject('purchaseRouteList', 'App\Common\KeyValueLists\PurchaseRouteList')


{{-- 受注情報編集フォーム：受注基本情報 --}}
<div class="card">
    <div class="card-header">
        {{__('admin.page_header_name.order_create')}}
        <div class="card-header-actions">
            @if($mode==='edit')
                <button type="button" class="btn btn-sm btn-primary" onclick="delivery_pdf();"><i class="fa fa-file-pdf-o"></i>&nbsp;{{__('admin.item_name.order.export_form')}}</button>
            @endif
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
                                <input name="order_id" type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if(isset($order) && isset($order['order_id'])){{$order['order_id']}}@endif">
                            </div>
                        </div>
                    </div>

                    {{-- 対応状況 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.status')}}</label>
                            <div class="col-8 col-lg-10">
                                <select name="order_status_id" class="form-control form-control-sm @isInvalid($errors,'order_status_id')" required>
                                    <option value=""></option>
                                    @php
                                        $old_order_status_id = !is_null(old('order_status_id')) ? old('order_status_id') :
                                                                ((isset($order) && isset($order['order_status_id'])) ? $order['order_status_id'] : 0);
                                    @endphp
                                    @if($mode==='edit')
                                        @foreach($orderStatusList->getPermittedList($old_order_status_id) as $id => $name)
                                            <option value="{{$id}}" @if($old_order_status_id==$id) selected @endif>{{$name}}</option>
                                        @endforeach
                                    @else
                                        @foreach($orderStatusList->getPermittedList(0) as $id => $name)
                                            <option value="{{$id}}" @if($old_order_status_id==$id) selected @endif>{{$name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="invalid-feedback">{{$errors->first('order_status_id')}}</div>
                            </div>
                        </div>
                    </div>
                    {{-- 購入方法 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__("admin.item_name.order.how_to_buy")}}</label>

                            <div class="col-8 col-lg-10">
                                <select name="purchase_route_id" class="form-control form-control-sm @isInvalid($errors,'purchase_route_id')" required>
                                    <option value=""></option>
                                    @foreach($purchaseRouteList as $id => $name)
                                        <option value="{{$id}}" @if(!is_null(old('purchase_route_id')) && old('purchase_route_id')==$id) selected @elseif (isset($order) && isset($order['purchase_route_id']) && $order['purchase_route_id'] == $id ) selected @endif>{{$name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">{{$errors->first('purchase_route_id')}}</div>
                            </div>
                        </div>
                    </div>
                    {{-- エラー表示 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.error_flag')}}</label>

                            <div class="col-8 col-lg-10 col-form-label col-form-label-sm">
                                <div class="form-check form-check-inline">
                                    <input name="display_purchase_warning_flag" class="form-check-input" type="radio" value="1" required @if (!is_null(old('display_purchase_warning_flag')) && old('display_purchase_warning_flag')) checked @elseif (isset($order) && isset($order['display_purchase_warning_flag']) && $order['display_purchase_warning_flag'] ) checked @endif>
                                    <label class="form-check-label">{{__('admin.item_name.common.do')}}</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input name="display_purchase_warning_flag" class="form-check-input" type="radio" value="0" @if (!is_null(old('display_purchase_warning_flag')) && !old('display_purchase_warning_flag')) checked @elseif (isset($order) && isset($order['display_purchase_warning_flag']) && !$order['display_purchase_warning_flag'] ) checked @endif>
                                    <label class="form-check-label">{{__('admin.item_name.common.dont')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- 備考 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__("admin.item_name.common.remark")}}</label>

                            <div class="col-8 col-lg-10">
                                <input name="message_from_customer" type="text" class="form-control form-control-sm @isInvalid($errors,'message_from_customer')" onchange="return app.functions.trim(this);"
                                       value="@if(!is_null(old('message_from_customer'))){{old('message_from_customer')}}@elseif(isset($order) && isset($order['message_from_customer'])){{$order['message_from_customer']}}@endif">
                                <div class="invalid-feedback">{{$errors->first('message_from_customer')}}</div>
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
                                       value="@if (isset($order) && isset($order['updated_at'])){{ date('Y/m/d', strtotime($order['updated_at']))}}@endif">
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
                                       value="@if (isset($shipping) && isset($shipping['scheduled_shipping_date'])){{ date('Y/m/d', strtotime($shipping['scheduled_shipping_date']))}}@endif">
                            </div>
                        </div>
                    </div>
                    {{-- 発送日 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-sm-4 col-form-label col-form-label-sm">{{__('admin.item_name.shipping.shipped_timestamp')}}</label>
                            <div class="col-sm-8" >
                                <input type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if (isset($shipping) && isset($shipping['shipped_timestamp'])){{ date('Y/m/d', strtotime($shipping['shipped_timestamp']))}}@endif">
                            </div>
                        </div>
                    </div>
                    {{-- 到着予定日 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-sm-4 col-form-label col-form-label-sm">{{__('admin.item_name.shipping.estimated_arrival_date')}}</label>
                            <div class="col-sm-8" >
                                <input type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if (isset($shipping) && isset($shipping['estimated_arrival_date'])) {{ date('Y/m/d', strtotime($shipping['estimated_arrival_date']))}}@endif">
                            </div>
                        </div>
                    </div>
                    {{-- キャンセル日 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-sm-4 col-form-label col-form-label-sm">{{__('admin.item_name.order.canceled_timestamp')}}</label>
                            <div class="col-sm-8" >
                                <input type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if (isset($order) && isset($order['canceled_timestamp'])) {{ date('Y/m/d', strtotime($order['canceled_timestamp']))}}@endif">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@if($mode==='edit')
    @push('content_js')
        <script>
            function delivery_pdf() {
                var order_id = $("input[name=order_id]").val();
                if (order_id) {
                    $("#delivery_pdf_form input.export_chk_list").remove();
                    const $form = $("#delivery_pdf_form");
                    $form.append('<input name="export_chk_list[]" type="hidden" value="' + order_id + '">');
                    $form.submit();
                }
            }
        </script>
    @endpush
@endif