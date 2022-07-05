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

                    {{-- 定期間隔 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-2 col-lg-1 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.interval')}}</label>
                            <div class="col-4 col-lg-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text form-control-sm">
                                        <input name="periodic_interval_type_id" type="radio" value="1" required
                                               @if (!is_null(old('periodic_interval_type_id')) && old('periodic_interval_type_id') == 1) checked @elseif (isset($order) && isset($order['periodic_interval_type_id']) && $order['periodic_interval_type_id'] == 1 ) checked @endif
                                                v-model="periodic_interval_type_id">
                                    </span>
                                    <input name="interval_days" type="number" min="10" max="120" class="form-control form-control-sm @isInvalid($errors,'interval_days')" placeholder="10 - 120" onchange="return app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);"
                                           :required="periodic_interval_type_id == 1"
                                           value="@if(!is_null(old('interval_days'))){{old('interval_days')}}@elseif(isset($order) && isset($order['interval_days'])){{$order['interval_days']}}@endif" >
                                    <span class="input-group-text form-control-sm">{{__('admin.item_name.common.date_unit')}}</span>
                                    <div class="invalid-feedback">{{$errors->first('interval_days')}}</div>
                                </div>
                            </div>

                            <div class="col-3 col-lg-3 pr-0">
                                <div class="input-group-prepend">
                                    <span class="input-group-text form-control-sm">
                                        <input name="periodic_interval_type_id" type="radio" value="2"
                                               @if (!is_null(old('periodic_interval_type_id')) && old('periodic_interval_type_id') == 2) checked @elseif (isset($order) && isset($order['periodic_interval_type_id']) && $order['periodic_interval_type_id'] == 2 ) checked @endif
                                                v-model="periodic_interval_type_id">
                                    </span>
                                    <input name="interval_month" type="number" min="1" max="6" class="form-control form-control-sm @isInvalid($errors,'interval_month')" placeholder="1 - 6" onchange="return app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);"
                                           :required="periodic_interval_type_id == 2"
                                           value="@if(!is_null(old('interval_month'))){{old('interval_month')}}@elseif(isset($order) && isset($order['interval_month'])){{$order['interval_month']}}@endif" >
                                    <span class="input-group-text form-control-sm">{{__('admin.item_name.periodic.interval_month')}}</span>
                                    <div class="invalid-feedback">{{$errors->first('interval_month')}}</div>
                                </div>
                            </div>
                            <div class="col-3 col-lg-3 pl-0">
                                <div class="input-group-prepend">
                                    <input name="interval_date_of_month" type="number" min="1" max="28" class="form-control form-control-sm @isInvalid($errors,'interval_date_of_month')" placeholder="1 - 28" onchange="return app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);"
                                           :required="periodic_interval_type_id == 2"
                                           value="@if(!is_null(old('interval_date_of_month'))){{old('interval_date_of_month')}}@elseif(isset($order) && isset($order['interval_date_of_month'])){{$order['interval_date_of_month']}}@endif" >
                                    <span class="input-group-text form-control-sm">{{__('admin.item_name.common.date_unit')}}</span>
                                    <div class="invalid-feedback">{{$errors->first('interval_date_of_month')}}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 次回到着日 --}}
                    <div class="col-6 col-lg-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.next_create_date')}}</label>
                            <div class="col-8 col-lg-10">
                                <input name="next_delivery_date" type="date" min="{{date('Y-m-d')}}" class="form-control form-control-sm @isInvalid($errors,'next_delivery_date')"
                                       value="@if(!is_null(old('next_delivery_date'))){{old('next_delivery_date')}}@elseif(isset($order) && isset($order['next_delivery_date'])){{$order['next_delivery_date']}}@endif">
                                <div class="invalid-feedback">{{$errors->first('next_delivery_date')}}</div>
                            </div>
                        </div>
                    </div>

                    {{-- 対応状況 --}}
                    <div class="col-6 col-lg-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.status')}}</label>
                            <div class="col-8 col-lg-10">
                                <select name="periodic_failed_flag" class="form-control form-control-sm @isInvalid($errors,'periodic_failed_flag')" required>
                                    @foreach($periodicOrderStatusList as $id => $name)
                                        <option value="{{$id}}" @if (!is_null(old('periodic_failed_flag')) && old('periodic_failed_flag')==$id) selected @elseif (isset($order) && isset($order['periodic_failed_flag']) && $order['periodic_failed_flag']==$id) selected @endif >{{$name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">{{$errors->first('periodic_failed_flag')}}</div>
                            </div>
                        </div>
                    </div>

                    {{-- 稼働状況 --}}
                    <div class="col-6 col-lg-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.stop_flag')}}</label>
                            <div class="col-8 col-lg-10">
                                <select name="periodic_stop_flag" class="form-control form-control-sm @isInvalid($errors,'periodic_stop_flag')" required>
                                    @foreach($periodicOrderStopFlagList as $id => $name)
                                        <option value="{{$id}}" @if (!is_null(old('periodic_stop_flag')) && old('periodic_stop_flag')==$id) selected @elseif (isset($order) && isset($order['periodic_stop_flag']) && $order['periodic_stop_flag']==$id) selected @endif >{{$name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">{{$errors->first('periodic_stop_flag')}}</div>
                            </div>
                        </div>
                    </div>

                    {{-- 購入方法 --}}
                    <div class="col-6 col-lg-6">
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

@push('content_js')
    <script>
        var order_basic_vue = new Vue({
            el: '#edit_order_basic_info_form',

            data: {
                periodic_interval_type_id: $('input[name=periodic_interval_type_id]:checked').val(),
            },
        });
    </script>
@endpush