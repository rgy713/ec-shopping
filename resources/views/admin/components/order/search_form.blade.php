@inject('orderStatusList', 'App\Common\KeyValueLists\OrderStatusList')
@inject('paymentList', 'App\Common\KeyValueLists\PaymentList')

{{--受注検索--}}
<div class="card">
    <div class="card-header">
        {{__('admin.page_header_name.order_search')}}
        <div class="card-header-actions">
            <label class="switch-sm switch-label switch-outline-primary-alt" role="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="true" aria-controls="search-order-body search-order-footer">
                <input class="switch-input" type="checkbox"  checked="">
                <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
            </label>
        </div>
    </div>

    <div id="search-order-body" class="card-body collapse multi-collapse show">
        <div class="row">
            <input id="sort_input" name="sort" type="hidden" value="@if(!is_null(old('sort'))){{old('sort')}}@elseif(isset($search_params['sort'])){{$search_params['sort']}}@endif">
            <input id="page_input" name="page" type="hidden" value="@if(!is_null(old('page'))){{old('page')}}@elseif(isset($search_params['page'])){{$search_params['page']}}@endif">

            {{-- 注文番号 --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-4 col-form-label col-form-label-sm">{{__('admin.item_name.order.id')}}</label>
                    <div class="col-8">
                        <input name="order_id" type="number" min="1" max="2147483647" class="form-control form-control-sm @isInvalid($errors,'order_id')" placeholder="{{__('admin.item_name.order.id')}}({{__('admin.item_name.common.whole_search')}})" onchange="return app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);"
                            value="@if(!is_null(old('order_id'))){{old('order_id')}}@elseif(isset($search_params['order_id'])){{$search_params['order_id']}}@endif"
                            v-model="order_id">
                        <div class="invalid-feedback">{{$errors->first('order_id')}}</div>
                    </div>
                </div>
            </div>

            {{-- 顧客ID --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-4 col-form-label col-form-label-sm">{{__('admin.item_name.customer.id')}}</label>
                    <div class="col-8">
                        <input name="order_customer_id" type="number" min="1" max="2147483647" class="form-control form-control-sm @isInvalid($errors,'order_customer_id')" placeholder="{{__('admin.item_name.customer.id')}}（{{__('admin.item_name.common.whole_search')}}）" onchange="return app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);"
                               value="@if(!is_null(old('order_customer_id'))){{old('order_customer_id')}}@elseif(isset($search_params['order_customer_id'])){{$search_params['order_customer_id']}}@endif"
                               v-model="order_customer_id">
                        <div class="invalid-feedback">{{$errors->first('order_customer_id')}}</div>
                    </div>
                </div>
            </div>

            {{-- 顧客名 --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-4 col-form-label col-form-label-sm">{{__('common.item_name.address.name')}}</label>
                    <div class="col-8">
                        <input name="order_customer_name" type="text" class="form-control form-control-sm @isInvalid($errors,'order_customer_name')" placeholder="{{__('common.item_name.address.name')}}（{{__('admin.item_name.common.partial_search')}}）" onchange="return app.functions.trim(this);"
                               value="@if(!is_null(old('order_customer_name'))){{old('order_customer_name')}}@elseif(isset($search_params['order_customer_name'])){{$search_params['order_customer_name']}}@endif"
                               v-model="order_customer_name">
                        <div class="invalid-feedback">{{$errors->first('order_customer_name')}}</div>
                    </div>
                </div>
            </div>

            {{-- 支払い方法 --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="col-2 col-lg-1 col-form-label col-form-label-sm">{{__("common.item_name.order.payment_method")}}</label>

                    <div class="col-10 col-lg-11 col-form-label col-form-label-sm">
                        @foreach($paymentList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input name="payment_method_id[]" type="checkbox" class="form-check-input" id="order-search-form-payment-{{$id}}" value="{{$id}}"
                                       @if(!is_null(old('payment_method_id')) && in_array($id, old('payment_method_id'))) checked @elseif(isset($search_params['payment_method_id']) && in_array($id, $search_params['payment_method_id'])) checked @endif>
                                <label for="order-search-form-payment-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>

            {{-- 対応状況 --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="col-2 col-lg-1 col-form-label col-form-label-sm">{{__("admin.item_name.order.status")}}</label>

                    <div class="col-10 col-lg-11 col-form-label col-form-label-sm">
                        @foreach($orderStatusList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input name="order_status_id[]" type="checkbox" class="form-check-input" id="order-search-form-status-{{$id}}" value="{{$id}}"
                                       @if(!is_null(old('order_status_id')) && in_array($id, old('order_status_id'))) checked @elseif(isset($search_params['order_status_id']) && in_array($id, $search_params['order_status_id'])) checked @endif>
                                <label for="order-search-form-status-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>

            {{-- 到着予定日 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__('admin.item_name.shipping.estimated_arrival_date')}}</label>
                    <div class="col-sm-5 pr-0">
                        <div class="input-group-prepend">
                            <input name="shippings_estimated_arrival_date_from" type="date" class="form-control form-control-sm @isInvalid($errors,'shippings_estimated_arrival_date_from')" placeholder="" onchange="shippings_estimated_arrival_date_from_changed();"
                                   value="@if(!is_null(old('shippings_estimated_arrival_date_from'))){{old('shippings_estimated_arrival_date_from')}}@elseif(isset($search_params['shippings_estimated_arrival_date_from'])){{$search_params['shippings_estimated_arrival_date_from']}}@endif">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                    </div>
                    <div class="col-sm-5 pl-0">
                        <div class="input-group-prepend">
                            <input name="shippings_estimated_arrival_date_to" type="date" class="form-control form-control-sm @isInvalid($errors,'shippings_estimated_arrival_date_to')" placeholder="" onchange="shippings_estimated_arrival_date_to_changed();"
                                   value="@if(!is_null(old('shippings_estimated_arrival_date_to'))){{old('shippings_estimated_arrival_date_to')}}@elseif(isset($search_params['shippings_estimated_arrival_date_to'])){{$search_params['shippings_estimated_arrival_date_to']}}@endif">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 受注日 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.create_date')}}</label>
                    <div class="col-sm-5 pr-0">
                        <div class="input-group-prepend">
                            <input name="order_created_at_from" type="date" class="form-control form-control-sm @isInvalid($errors,'order_created_at_from')" placeholder="" onchange="order_created_at_from_changed();"
                                   value="@if(!is_null(old('order_created_at_from'))){{old('order_created_at_from')}}@elseif(isset($search_params['order_created_at_from'])){{$search_params['order_created_at_from']}}@endif">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                    </div>
                    <div class="col-sm-5 pl-0">
                        <div class="input-group-prepend">
                            <input name="order_created_at_to" type="date" class="form-control form-control-sm @isInvalid($errors,'order_created_at_to')" placeholder="" onchange="order_created_at_to_changed();"
                                   value="@if(!is_null(old('order_created_at_to'))){{old('order_created_at_to')}}@elseif(isset($search_params['order_created_at_to'])){{$search_params['order_created_at_to']}}@endif">
                        </div>
                    </div>
                </div>
            </div>

            {{-- キャンセル日 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.canceled_timestamp')}}</label>
                    <div class="col-sm-5 pr-0">
                        <div class="input-group-prepend">
                            <input name="order_canceled_timestamp_from" type="date" class="form-control form-control-sm @isInvalid($errors,'order_canceled_timestamp_from')" placeholder="" onchange="order_canceled_timestamp_from_changed();"
                                   value="@if(!is_null(old('order_canceled_timestamp_from'))){{old('order_canceled_timestamp_from')}}@elseif(isset($search_params['order_canceled_timestamp_from'])){{$search_params['order_canceled_timestamp_from']}}@endif">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                    </div>
                    <div class="col-sm-5 pl-0">
                        <div class="input-group-prepend">
                            <input name="order_canceled_timestamp_to" type="date" class="form-control form-control-sm @isInvalid($errors,'order_canceled_timestamp_to')" placeholder="" onchange="order_canceled_timestamp_to_changed();"
                                   value="@if(!is_null(old('order_canceled_timestamp_to'))){{old('order_canceled_timestamp_to')}}@elseif(isset($search_params['order_canceled_timestamp_to'])){{$search_params['order_canceled_timestamp_to']}}@endif">
                        </div>
                    </div>
                </div>
            </div>

            {{-- オプション条件 --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <div class="col-12 col-lg-12 col-form-label col-form-label-sm">

                        <div class="form-check form-check-inline">
                            <input name="order_purchase_warning_flag" type="checkbox" class="form-check-input" id="search-checkbox-03-1" value="1"
                                   @if(!is_null(old('order_purchase_warning_flag'))) checked @elseif(isset($search_params['order_purchase_warning_flag']) && $search_params['order_purchase_warning_flag']) checked @endif>
                            <label for="search-checkbox-03-1" class="form-check-label">{{__('admin.item_name.order.purchase_warning_flag')}}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input name="message_from_customer" type="checkbox" class="form-check-input" id="search-checkbox-03-2" value="1"
                                   @if(!is_null(old('message_from_customer'))) checked @elseif(isset($search_params['message_from_customer']) && $search_params['message_from_customer']) checked @endif>
                            <label for="search-checkbox-03-2" class="form-check-label">{{__('admin.item_name.order.message_from_customer')}}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input name="order_no_payment" type="checkbox" class="form-check-input" id="search-checkbox-03-3" value="1"
                                   @if(!is_null(old('order_no_payment'))) checked @elseif(isset($search_params['order_no_payment']) && $search_params['order_no_payment']) checked @endif>
                            <label for="search-checkbox-03-3" class="form-check-label">{{__('admin.item_name.order.no_payment')}}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input name="order_bundle_shippings" type="checkbox" class="form-check-input" id="search-checkbox-03-4" value="1"
                                   @if(!is_null(old('order_bundle_shippings'))) checked @elseif(isset($search_params['order_bundle_shippings']) && $search_params['order_bundle_shippings']) checked @endif>
                            <label for="search-checkbox-03-4" class="form-check-label">{{__('admin.item_name.order.bundle_shippings')}}</label>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@push('content_js')
    <script>
        function shippings_estimated_arrival_date_from_changed() {
            $('input[name=shippings_estimated_arrival_date_to]').attr('min', $('input[name=shippings_estimated_arrival_date_from]').val());
        }
        function shippings_estimated_arrival_date_to_changed() {
            $('input[name=shippings_estimated_arrival_date_from]').attr('max', $('input[name=shippings_estimated_arrival_date_to]').val());
        }
        function order_created_at_from_changed() {
            $('input[name=order_created_at_to]').attr('min', $('input[name=order_created_at_from]').val());
        }
        function order_created_at_to_changed() {
            $('input[name=order_created_at_from]').attr('max', $('input[name=order_created_at_to]').val());
        }
        function order_canceled_timestamp_from_changed() {
            $('input[name=order_canceled_timestamp_to]').attr('min', $('input[name=order_canceled_timestamp_from]').val());
        }
        function order_canceled_timestamp_to_changed() {
            $('input[name=order_canceled_timestamp_from]').attr('max', $('input[name=order_canceled_timestamp_to]').val());
        }

        shippings_estimated_arrival_date_from_changed();
        shippings_estimated_arrival_date_to_changed();
        order_created_at_from_changed();
        order_created_at_to_changed();
        order_canceled_timestamp_from_changed();
        order_canceled_timestamp_to_changed();
    </script>
@endpush