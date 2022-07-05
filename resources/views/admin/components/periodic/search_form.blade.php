@inject('periodicOrderStatusList', 'App\Common\KeyValueLists\PeriodicOrderStatusList')
@inject('periodicOrderStopFlagList', 'App\Common\KeyValueLists\PeriodicOrderStopFlagList')
@inject('itemLineupList', 'App\Common\KeyValueLists\ItemLineupList')
@inject('paymentList', 'App\Common\KeyValueLists\PaymentList')

{{--定期検索--}}
<div class="card">
    <div class="card-header">
        {{__('admin.page_header_name.periodic_search')}}
        <div class="card-header-actions">
            <label class="switch-sm switch-label switch-outline-primary-alt" role="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="true" aria-controls="search-periodic-body">
                <input class="switch-input" type="checkbox"  checked="">
                <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
            </label>
        </div>
    </div>

    <div id="search-periodic-body" class="card-body collapse multi-collapse show">
        <div class="row">
            <input id="sort_input" name="sort" type="hidden" value="@if(old('sort')){{old('sort')}}@elseif(isset($search_params['sort'])){{$search_params['sort']}}@endif">
            <input id="page_input" name="page" type="hidden" value="@if(old('page')){{old('page')}}@elseif(isset($search_params['page'])){{$search_params['page']}}@endif">

            {{-- 定期ID --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-4 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.id')}}</label>
                    <div class="col-8">
                        <input name="periodic_order_id" type="number" min="1" max="2147483647"  class="form-control form-control-sm @isInvalid($errors,'periodic_order_id')" placeholder="{{__('admin.item_name.periodic.id')}}({{__('admin.item_name.common.whole_search')}})" onchange="return app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);"
                               value="@if(old('periodic_order_id')){{old('periodic_order_id')}}@elseif(isset($search_params['periodic_order_id'])){{$search_params['periodic_order_id']}}@endif">
                        <div class="invalid-feedback">{{$errors->first('periodic_order_id')}}</div>
                    </div>
                </div>
            </div>

            {{-- 顧客ID --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-4 col-form-label col-form-label-sm">{{__('admin.item_name.customer.id')}}</label>
                    <div class="col-8">
                        <input name="periodic_order_customer_id" type="number" min="1" max="2147483647" class="form-control form-control-sm @isInvalid($errors,'periodic_order_customer_id')" placeholder="{{__('admin.item_name.customer.id')}}({{__('admin.item_name.common.whole_search')}})" onchange="return app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);"
                               value="@if(old('periodic_order_customer_id')){{old('periodic_order_customer_id')}}@elseif(isset($search_params['periodic_order_customer_id'])){{$search_params['periodic_order_customer_id']}}@endif">
                        <div class="invalid-feedback">{{$errors->first('periodic_order_customer_id')}}</div>
                    </div>
                </div>
            </div>

            {{-- お名前 --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-4 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.name')}}</label>
                    <div class="col-8">
                        <input name="periodic_order_customer_name" type="text" class="form-control form-control-sm @isInvalid($errors,'periodic_order_customer_name')" placeholder="{{__('admin.item_name.periodic.name')}}({{__('admin.item_name.common.partial_search')}})" onchange="return app.functions.trim(this);"
                               value="@if(old('periodic_order_customer_name')){{old('periodic_order_customer_name')}}@elseif(isset($search_params['periodic_order_customer_name'])){{$search_params['periodic_order_customer_name']}}@endif">
                        <div class="invalid-feedback">{{$errors->first('periodic_order_customer_name')}}</div>
                    </div>
                </div>
            </div>

            {{-- カナ --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-4 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.kana')}}</label>
                    <div class="col-8">
                        <input name="periodic_order_customer_kana" type="text" pattern="[\u30A0-\u30FF]*" title="{{__('validation.hint_text.customer_kana')}}" class="form-control form-control-sm @isInvalid($errors,'periodic_order_customer_kana')" placeholder="{{__('admin.item_name.periodic.kana')}}({{__('admin.item_name.common.partial_search')}})" onchange="return app.functions.trim(this);"
                               value="@if(old('periodic_order_customer_kana')){{old('periodic_order_customer_kana')}}@elseif(isset($search_params['periodic_order_customer_kana'])){{$search_params['periodic_order_customer_kana']}}@endif">
                        <div class="invalid-feedback">{{$errors->first('periodic_order_customer_kana')}}</div>
                    </div>
                </div>
            </div>

            {{-- 対応状況 --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-4 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.status')}}</label>
                    <div class="col-8">
                        <select name="periodic_order_failed_flag" class="form-control form-control-sm @isInvalid($errors,'periodic_order_failed_flag')">
                            <option value=""></option>
                            @foreach($periodicOrderStatusList as $id => $name)
                                <option value="{{$id}}" @if(!is_null(old('periodic_order_failed_flag')) && $id==old('periodic_order_failed_flag')) selected @elseif(isset($search_params['periodic_order_failed_flag']) && $search_params['periodic_order_failed_flag']==$id) selected @endif>{{$name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 稼働状況 --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-4 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.stop_flag')}}</label>
                    <div class="col-8">
                        <select name="periodic_order_stop_flag" class="form-control form-control-sm @isInvalid($errors,'periodic_order_stop_flag')">
                            <option value=""></option>
                            @foreach($periodicOrderStopFlagList as $id => $name)
                                <option value="{{$id}}" @if(!is_null(old('periodic_order_stop_flag')) && $id==old('periodic_order_stop_flag')) selected @elseif(isset($search_params['periodic_order_stop_flag']) && $search_params['periodic_order_stop_flag']==$id) selected @endif>{{$name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 定期回 --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-4 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.count')}}</label>
                    <div class="col-8">
                        <input name="periodic_count"  type="number" min="0" max="32767" step="1" class="form-control form-control-sm @isInvalid($errors,'periodic_count')" placeholder="" onchange="return app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);"
                               value="@if(old('periodic_count')){{old('periodic_count')}}@elseif(isset($search_params['periodic_count'])){{$search_params['periodic_count']}}@endif">
                        <div class="invalid-feedback">{{$errors->first('periodic_count')}}</div>
                    </div>
                </div>
            </div>

            {{-- 余白 --}}
            <div class="col-6 col-lg-3"></div>

            {{-- 商品ラインナップ --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="col-2 col-lg-1 col-form-label col-form-label-sm">{{__('admin.item_name.product.lineup')}}</label>
                    <div class="col-10 col-lg-11 col-form-label col-form-label-sm">
                        @foreach($itemLineupList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input name="item_lineup_id[]" class="form-check-input" type="checkbox" id="inlineCheckbox-20-{{$id}}" value="{{$id}}"
                                       @if(old('item_lineup_id') && in_array($id, old('item_lineup_id'))) checked @elseif(isset($search_params['item_lineup_id']) && in_array($id, $search_params['item_lineup_id'])) checked @endif>
                                <label for="inlineCheckbox-20-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
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
                                <input name="payment_method_id[]" class="form-check-input" type="checkbox" id="order-search-form-payment-{{$id}}" value="{{$id}}"
                                       @if(old('payment_method_id') && in_array($id, old('payment_method_id'))) checked @elseif(isset($search_params['payment_method_id']) && in_array($id, $search_params['payment_method_id'])) checked @endif>
                                <label for="order-search-form-payment-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 前回到着日 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.prev_create_date')}}</label>
                    <div class="col-sm-5 pr-0">
                        <div class="input-group-prepend">
                            <input name="periodic_order_last_delivery_date_from" class="form-control form-control-sm @isInvalid($errors,'periodic_order_last_delivery_date_from')" type="date" placeholder="" onchange="periodic_order_last_delivery_date_from_changed();"
                                   value="@if(old('periodic_order_last_delivery_date_from')){{old('periodic_order_last_delivery_date_from')}}@elseif(isset($search_params['periodic_order_last_delivery_date_from'])){{$search_params['periodic_order_last_delivery_date_from']}}@endif">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                    </div>

                    <div class="col-sm-5 pl-0">
                        <input name="periodic_order_last_delivery_date_to" class="form-control form-control-sm @isInvalid($errors,'periodic_order_last_delivery_date_to')" type="date" placeholder="" onchange="periodic_order_last_delivery_date_to_changed();"
                               value="@if(old('periodic_order_last_delivery_date_to')){{old('periodic_order_last_delivery_date_to')}}@elseif(isset($search_params['periodic_order_last_delivery_date_to'])){{$search_params['periodic_order_last_delivery_date_to']}}@endif">
                    </div>
                </div>
            </div>

            {{-- 次回到着日 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.next_create_date')}}</label>
                    <div class="col-sm-5 pr-0">
                        <div class="input-group-prepend">
                            <input name="periodic_order_next_delivery_date_from" class="form-control form-control-sm @isInvalid($errors,'periodic_order_next_delivery_date_from')" type="date" placeholder="" onchange="periodic_order_next_delivery_date_from_changed();"
                                   value="@if(old('periodic_order_next_delivery_date_from')){{old('periodic_order_next_delivery_date_from')}}@elseif(isset($search_params['periodic_order_next_delivery_date_from'])){{$search_params['periodic_order_next_delivery_date_from']}}@endif">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                    </div>

                    <div class="col-sm-5 pl-0">
                        <input name="periodic_order_next_delivery_date_to" class="form-control form-control-sm @isInvalid($errors,'periodic_order_next_delivery_date_to')" type="date" placeholder="" onchange="periodic_order_next_delivery_date_to_changed();"
                               value="@if(old('periodic_order_next_delivery_date_to')){{old('periodic_order_next_delivery_date_to')}}@elseif(isset($search_params['periodic_order_next_delivery_date_to'])){{$search_params['periodic_order_next_delivery_date_to']}}@endif">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('content_js')
    <script>
        function periodic_order_last_delivery_date_from_changed() {
            $('input[name=periodic_order_last_delivery_date_to]').attr('min', $('input[name=periodic_order_last_delivery_date_from]').val());
        }
        function periodic_order_last_delivery_date_to_changed() {
            $('input[name=periodic_order_last_delivery_date_from]').attr('max', $('input[name=periodic_order_last_delivery_date_to]').val());
        }
        function periodic_order_next_delivery_date_from_changed() {
            $('input[name=periodic_order_next_delivery_date_to]').attr('min', $('input[name=periodic_order_next_delivery_date_from]').val());
        }
        function periodic_order_next_delivery_date_to_changed() {
            $('input[name=periodic_order_next_delivery_date_from]').attr('max', $('input[name=periodic_order_next_delivery_date_to]').val());
        }

        periodic_order_last_delivery_date_from_changed();
        periodic_order_last_delivery_date_to_changed();
        periodic_order_next_delivery_date_from_changed();
        periodic_order_next_delivery_date_to_changed();
    </script>
@endpush