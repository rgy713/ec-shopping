@inject('prefectureList', 'App\Common\KeyValueLists\PrefectureList')
@inject('mediaCodeList', 'App\Common\KeyValueLists\MediaCodeList')
@inject('customerStatusList', 'App\Common\KeyValueLists\CustomerStatusList')
@inject('periodicOrderStopFlagList', 'App\Common\KeyValueLists\PeriodicOrderStopFlagList')
@inject('periodicOrderStatusList', 'App\Common\KeyValueLists\PeriodicOrderStatusList')
@inject('itemLineupList', 'App\Common\KeyValueLists\ItemLineupList')
@inject('paymentList', 'App\Common\KeyValueLists\PaymentList')

{{-- 詳細検索の検索フォーム --}}
<div class="card">
    <div class="card-header">
        {{__('admin.page_header_name.customer_information')}}
        <div class="card-header-actions">
            <label class="switch-sm switch-label switch-outline-primary-alt" role="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="true" aria-controls="search-customer-form-body search-customer-form-footer">
                <input class="switch-input" type="checkbox"  checked="">
                <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
            </label>
        </div>
    </div>
    <div id="search-customer-form-body" class="card-body collapse multi-collapse show">

        <div class="row">
            <input id="sort_input" name="sort" type="hidden" value="@if(old('sort')){{old('sort')}}@elseif(isset($search_params)){{$search_params['sort']}}@endif">
            <input id="page_input" name="page" type="hidden" value="@if(old('page')){{old('page')}}@elseif(isset($search_params)){{$search_params['page']}}@endif">

            {{-- 顧客ID --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label col-form-label-sm" >{{__("admin.item_name.customer.id")}}</label>
                    <div class="col-sm-8">
                        <input class="form-control form-control-sm @isInvalid($errors,'customer_id')" name="customer_id" type="number" min="1" max="2147483647" placeholder="顧客ID（完全一致）" value="@if(old('customer_id')){{old('customer_id')}}@elseif(isset($search_params)){{$search_params['customer_id']}}@endif" onchange="return app.functions.trim(this);" v-on:keypress="only_number">
                        <div class="invalid-feedback">{{$errors->first('customer_id')}}</div>
                    </div>
                </div>
            </div>

            {{-- 都道府県 --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label col-form-label-sm">{{__("admin.item_name.address.prefecture")}}</label>
                    <div class="col-sm-8">
                        <select class="form-control form-control-sm" name="customer_prefecture_id">
                            <option></option>
                            @foreach($prefectureList as $id => $name)
                                <option value="{{$id}}" @if(old('customer_prefecture_id') && $id==old('customer_prefecture_id')) selected @elseif(isset($search_params) && $id==$search_params['customer_prefecture_id']) selected @endif>{{$name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 顧客名 --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label col-form-label-sm">{{__("common.item_name.address.name")}}</label>
                    <div class="col-sm-8">
                        <input class="form-control form-control-sm" name="customer_name" type="text" placeholder="お名前（部分一致）" value="@if(old('customer_name')){{old('customer_name')}}@elseif(isset($search_params)){{$search_params['customer_name']}}@endif" onchange="return app.functions.trim(this);">
                    </div>
                </div>
            </div>

            {{-- フリガナ --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label col-form-label-sm">{{__("common.item_name.address.kana")}}</label>
                    <div class="col-sm-8">
                        <input class="form-control form-control-sm" name="customer_kana" type="text" placeholder="フリガナ（部分一致）" value="@if(old('customer_kana')){{old('customer_kana')}}@elseif(isset($search_params)){{$search_params['customer_kana']}}@endif" onchange="return app.functions.trim(this);">
                    </div>
                </div>
            </div>

            {{-- 広告番号 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__("admin.item_name.media.code")}}</label>
                    <div class="col-sm-10">
                        <select class="form-control form-control-sm" name="customer_media_code">
                            <option></option>
                            @foreach($mediaCodeList as $id => $name)
                                <option value="{{$id}}" @if(old('customer_media_code') && $id==old('customer_media_code')) selected @elseif(isset($search_params) &&  $id==$search_params['customer_media_code']) selected @endif>{{$name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 誕生日 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__("admin.item_name.customer.birthday")}}</label>
                    <div class="col-sm-5" >
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm" name="customer_birthday_from" type="date" placeholder="" value="@if(old('customer_birthday_from')){{old('customer_birthday_from')}}@elseif(isset($search_params)){{$search_params['customer_birthday_from']}}@endif"  v-on:change="min_max_date($event,'customer_birthday_to', 0)">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                    </div>

                    <div class="col-sm-5" >
                        <input class="form-control form-control-sm @isInvalid($errors,'customer_birthday_to')" name="customer_birthday_to" type="date" placeholder="" value="@if(old('customer_birthday_to')){{old('customer_birthday_to')}}@elseif(isset($search_params)){{$search_params['customer_birthday_to']}}@endif" v-on:change="min_max_date($event,'customer_birthday_from', 1)" >
                    </div>
                </div>
            </div>

            {{-- メールアドレス --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label col-form-label-sm">{{__("admin.item_name.customer.email")}}</label>
                    <div class="col-sm-8">
                        <input class="form-control form-control-sm" name="customer_email" type="text" placeholder="メールアドレス（完全一致）" value="@if(old('customer_email')){{old('customer_email')}}@elseif(isset($search_params)){{$search_params['customer_email']}}@endif" onchange="return app.functions.trim(this);">
                    </div>
                </div>
            </div>

            {{-- 電話番号 --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label col-form-label-sm">{{__("common.item_name.address.tel")}}</label>
                    <div class="col-sm-8">
                        <input class="form-control form-control-sm" name="customer_phone" type="text" placeholder="TEL（完全一致）" title="TEL1、TEL2どちらも検索可能" value="@if(old('customer_phone')){{old('customer_phone')}}@elseif(isset($search_params)){{$search_params['customer_phone']}}@endif" onchange="return app.functions.trim(this);">
                    </div>
                </div>
            </div>

            {{-- 購入金額 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__("admin.item_name.customer.buy_total")}}</label>
                    <div class="col-sm-5" >
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm @isInvalid($errors,'customer_buy_total_min')" name="customer_buy_total_min" type="number" min="0" max="2147483647" placeholder="購入金額（下限）" value="@if(old('customer_buy_total_min')){{old('customer_buy_total_min')}}@elseif(isset($search_params)){{$search_params['customer_buy_total_min']}}@endif" v-on:keypress="only_number">
                            <span class="input-group-text form-control-sm">円～</span>
                        </div>
                    </div>

                    <div class="col-sm-5" >
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm @isInvalid($errors,'customer_buy_total_max')" name="customer_buy_total_max" type="number" min="0" max="2147483647" placeholder="購入金額（上限）" value="@if(old('customer_buy_total_max')){{old('customer_buy_total_max')}}@elseif(isset($search_params)){{$search_params['customer_buy_total_max']}}@endif" v-on:keypress="only_number">
                            <span class="input-group-text form-control-sm">円</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 最終購入日 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__('admin.item_name.customer.last_buy_date')}}</label>
                    <div class="col-sm-5" >
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm" name="customer_last_buy_date_from" type="date" placeholder="" value="@if(old('customer_last_buy_date_from')){{old('customer_last_buy_date_from')}}@elseif(isset($search_params)){{$search_params['customer_last_buy_date_from']}}@endif" v-on:change="min_max_date($event,'customer_last_buy_date_to', 0)">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                    </div>
                    <div class="col-sm-5" >
                        <input class="form-control form-control-sm @isInvalid($errors,'customer_last_buy_date_to')" name="customer_last_buy_date_to" type="date" placeholder="" value="@if(old('customer_last_buy_date_to')){{old('customer_last_buy_date_to')}}@elseif(isset($search_params)){{$search_params['customer_last_buy_date_to']}}@endif" v-on:change="min_max_date($event,'customer_last_buy_date_from', 1)">
                    </div>
                </div>
            </div>

            {{-- 購入商品名 --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label col-form-label-sm" >{{__("admin.item_name.product.name")}}</label>
                    <div class="col-sm-8">
                        <input class="form-control form-control-sm" name="customer_product_name" type="text"  placeholder="購入商品名（部分一致）" value="@if(old('customer_product_name')){{old('customer_product_name')}}@elseif(isset($search_params)){{$search_params['customer_product_name']}}@endif" onchange="return app.functions.trim(this);">
                    </div>
                </div>
            </div>

            {{-- 購入商品コード --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label col-form-label-sm" >{{__("admin.item_name.product.code")}}</label>
                    <div class="col-sm-8">
                        <input class="form-control form-control-sm" name="customer_product_code" type="text" placeholder="購入商品コード（完全一致）" value="@if(old('customer_product_code')){{old('customer_product_code')}}@elseif(isset($search_params)){{$search_params['customer_product_code']}}@endif" onchange="return app.functions.trim(this);">
                    </div>
                </div>
            </div>

            {{-- 会員状態 --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label col-form-label-sm" >{{__("admin.item_name.customer.status")}}</label>
                    <div class="col-sm-8 col-form-label col-form-label-sm">
                        @foreach($customerStatusList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customer_status[]" id="customer-detailed-search-customer-status-{{$id}}" value="{{$id}}"
                                    @if(old('customer_status'))
                                        @foreach(old('customer_status') as $status)
                                            @if($id==$status) checked @endif
                                        @endforeach
                                    @elseif(isset($search_params['customer_status']))
                                        @foreach($search_params['customer_status'] as $status)
                                            @if($id==$status) checked @endif
                                        @endforeach
                                    @endif
                                >
                                <label for="customer-detailed-search-customer-status-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-header">
        {{__('admin.page_header_name.periodic_information')}}
        <div class="card-header-actions">
        </div>
    </div>

    {{-- 定期条件フォーム:start --}}
    <div id="search-periodic-form-body" class="card-body collapse multi-collapse show">
        <div class="row">
            {{-- 定期ID --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label col-form-label-sm">{{__("admin.item_name.periodic.id")}}</label>
                    <div class="col-sm-5 pr-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm @isInvalid($errors,'customer_periodic_id_min')" name="customer_periodic_id_min" type="number" min="0" max="2147483647" placeholder="定期ID" value="@if(old('customer_periodic_id_min')){{old('customer_periodic_id_min')}}@elseif(isset($search_params)){{$search_params['customer_periodic_id_min']}}@endif" onchange="return app.functions.trim(this);" v-on:keypress="only_number">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                    </div>

                    <div class="col-sm-3 pl-0" >
                        <input class="form-control form-control-sm @isInvalid($errors,'customer_periodic_id_max')" name="customer_periodic_id_max" type="number" min="0" max="2147483647" placeholder="定期ID" value="@if(old('customer_periodic_id_max')){{old('customer_periodic_id_max')}}@elseif(isset($search_params)){{$search_params['customer_periodic_id_max']}}@endif" onchange="return app.functions.trim(this);" v-on:keypress="only_number">
                    </div>
                </div>
            </div>

            {{-- 定期回数 --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label col-form-label-sm">{{__("admin.item_name.periodic.count")}}</label>
                    <div class="col-sm-4 pr-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm @isInvalid($errors,'customer_periodic_count_min')" name="customer_periodic_count_min" type="number" min="0" max="32767" placeholder="" value="@if(old('customer_periodic_count_min')){{old('customer_periodic_count_min')}}@elseif(isset($search_params)){{$search_params['customer_periodic_count_min']}}@endif" v-on:keypress="only_number">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                    </div>
                    <div class="col-sm-4 pl-0" >
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm @isInvalid($errors,'customer_periodic_count_max')" name="customer_periodic_count_max" type="number" min="0" max="32767" placeholder="" value="@if(old('customer_periodic_count_max')){{old('customer_periodic_count_max')}}@elseif(isset($search_params)){{$search_params['customer_periodic_count_max']}}@endif" onchange="return app.functions.trim(this);" v-on:keypress="only_number">
                            <span class="input-group-text form-control-sm">回</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 対応状況 --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label col-form-label-sm">{{__("admin.item_name.periodic.status")}}</label>
                    <div class="col-sm-8">
                        <select class="form-control form-control-sm" name="customer_periodic_status_flag">
                            <option></option>
                            @foreach($periodicOrderStatusList as $id => $name)
                                <option value="{{$id}}" @if(null!==old('customer_periodic_status_flag') && $id==old('customer_periodic_status_flag')) selected @elseif(isset($search_params['customer_periodic_status_flag']) && $search_params['customer_periodic_status_flag']==$id) selected @endif>{{$name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 稼働状況 --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label col-form-label-sm">{{__("admin.item_name.periodic.stop_flag")}}</label>
                    <div class="col-sm-8">
                        <select class="form-control form-control-sm" name="customer_periodic_stop_flag">
                            <option></option>
                            @foreach($periodicOrderStopFlagList as $id => $name)
                                <option value="{{$id}}" @if(null!==old('customer_periodic_stop_flag') && $id==old('customer_periodic_stop_flag')) selected @elseif(isset($search_params['customer_periodic_stop_flag']) && $search_params['customer_periodic_stop_flag']==$id) selected @endif>{{$name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 分類 --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="col-2 col-lg-1 col-form-label col-form-label-sm">{{__('admin.item_name.product.lineup')}}</label>
                    <div class="col-10 col-lg-11 col-form-label col-form-label-sm">
                        @foreach($itemLineupList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="customer_product_lineup[]" type="checkbox" id="customer-detailed-search-periodic-item-lineup-{{$id}}" value="{{$id}}"
                                       @if(old('customer_product_lineup'))
                                           @foreach(old('customer_product_lineup') as $lineup)
                                               @if($id==$lineup) checked @endif
                                           @endforeach
                                       @elseif(isset($search_params['customer_product_lineup']))
                                           @foreach($search_params['customer_product_lineup'] as $lineup)
                                               @if($id==$lineup) checked @endif
                                           @endforeach
                                       @endif
                                >
                                <label for="customer-detailed-search-periodic-item-lineup-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 支払い方法 --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="col-2 col-lg-1 col-form-label col-form-label-sm">{{__('admin.item_name.order.payment_method')}}</label>
                    <div class="col-10 col-lg-11 col-form-label col-form-label-sm">
                        @foreach($paymentList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customer_payment_method[]" id="customer-detailed-search-periodic-payment-{{$id}}" value="{{$id}}"
                                       @if(old('customer_payment_method'))
                                           @foreach(old('customer_payment_method') as $payment_method)
                                               @if($id==$payment_method) checked @endif
                                           @endforeach
                                       @elseif(isset($search_params['customer_payment_method']))
                                           @foreach($search_params['customer_payment_method'] as $payment_method)
                                               @if($id==$payment_method) checked @endif
                                           @endforeach
                                       @endif
                                >
                                <label for="customer-detailed-search-periodic-payment-{{$id}}" class="form-check-label">{{$name}}</label>
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
                            <input class="form-control form-control-sm" name="customer_periodic_prev_create_from" type="date" placeholder="" value="@if(old('customer_periodic_prev_create_from')){{old('customer_periodic_prev_create_from')}}@elseif(isset($search_params)){{$search_params['customer_periodic_prev_create_from']}}@endif" v-on:change="min_max_date($event,'customer_periodic_prev_create_to', 0)">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                    </div>
                    <div class="col-sm-5 pl-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm @isInvalid($errors,'customer_periodic_prev_create_to')" name="customer_periodic_prev_create_to" type="date" placeholder="" value="@if(old('customer_periodic_prev_create_to')){{old('customer_periodic_prev_create_to')}}@elseif(isset($search_params)){{$search_params['customer_periodic_prev_create_to']}}@endif" v-on:change="min_max_date($event,'customer_periodic_prev_create_from', 1)">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 次回到着日 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm" >{{__('admin.item_name.periodic.next_create_date')}}</label>
                    <div class="col-sm-5 pr-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm" name="customer_periodic_next_create_from" type="date" placeholder="" value="@if(old('customer_periodic_next_create_from')){{old('customer_periodic_next_create_from')}}@elseif(isset($search_params)){{$search_params['customer_periodic_next_create_from']}}@endif" v-on:change="min_max_date($event,'customer_periodic_next_create_to', 0)">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                    </div>

                    <div class="col-sm-5 pl-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm @isInvalid($errors,'customer_periodic_next_create_to')" name="customer_periodic_next_create_to" type="date" placeholder="" value="@if(old('customer_periodic_next_create_to')){{old('customer_periodic_next_create_to')}}@elseif(isset($search_params)){{$search_params['customer_periodic_next_create_to']}}@endif" v-on:change="min_max_date($event,'customer_periodic_next_create_from', 1)">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- 定期条件フォーム:end --}}

</div>
