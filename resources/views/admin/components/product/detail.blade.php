@inject('itemLineupList', 'App\Common\KeyValueLists\ItemLineupList')
@inject('SalesTargetList', 'App\Common\KeyValueLists\SalesTargetList')
@inject('salesRouteList', 'App\Common\KeyValueLists\SalesRouteList')
@inject('productTypeList', 'App\Common\KeyValueLists\ProductTypeList')
@inject('productVolumeList', 'App\Common\KeyValueLists\ProductVolumeList')
@inject('stockKeepingUnitList', 'App\Common\KeyValueLists\StockKeepingUnitList')
@inject('undeliveredSummaryClassificationList', 'App\Common\KeyValueLists\UndeliveredSummaryClassificationList')
@inject('marketingSummaryClassificationList', 'App\Common\KeyValueLists\MarketingSummaryClassificationList')
@inject('productCodeList', 'App\Common\KeyValueLists\ProductCodeList')
@inject('paymentList', 'App\Common\KeyValueLists\PaymentList')
@inject('periodicIntervalTypeList', 'App\Common\KeyValueLists\PeriodicIntervalTypeList')
@inject('fixedPeriodicIntervalList', 'App\Common\KeyValueLists\FixedPeriodicIntervalList')
@inject('productVisibleList', 'App\Common\KeyValueLists\ProductVisibleList')

<div class="card">
    <div class="card-header">
        @if(isset($form_title)){{$form_title}}@endif
        <div class="card-header-actions">
        </div>
    </div>

    <div id="edit-product-body" class="card-body collapse multi-collapse show">
        <div class="row">

            {{-- 商品ID --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm" >{{__('admin.item_name.product.id')}}</label>
                    <div class="col-9">
                        <input type="text" readonly class="form-control-plaintext form-control-sm" value="@if(isset($product)){{$product->id}}@endif">
                    </div>
                </div>
            </div>

            {{-- 商品名 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm" >{{__('admin.item_name.product.name')}}</label>
                    <div class="col-9">
                        <input name="product_name" type="text" class="form-control form-control-sm @isInvalid($errors,'product_name')" onchange="return app.functions.trim(this);" required
                               value="@if(!is_null(old('product_name'))){{ old('product_name') }}@elseif(isset($product)){{$product->name}}@endif" >
                        <small class="form-text text-muted">{{__("admin.help_text.product.name_length")}}</small>
                        <div class="invalid-feedback">{{$errors->first('product_name')}}</div>
                    </div>
                </div>
            </div>

            {{-- 商品コード --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm" >{{__('admin.item_name.product.code')}}</label>
                    <div class="col-9">
                        <input name="product_code" type="text" class="form-control form-control-sm  @isInvalid($errors,'product_code')" onchange="return app.functions.trim(this);" required
                               value="@if(!is_null(old('product_code'))){{ old('product_code') }}@elseif(isset($product)){{$product->code}}@endif" >
                        <div class="invalid-feedback">{{$errors->first('product_code')}}</div>
                    </div>
                </div>
            </div>

            {{-- ラインナップ --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.product.lineup')}}</label>
                    <div class="col-9">
                        <select name="item_lineup_id" class="form-control form-control-sm @isInvalid($errors,'item_lineup_id')" required>
                            <option value=""></option>
                            @foreach($itemLineupList as $id => $name)
                                <option value="{{$id}}" @if(!is_null(old('item_lineup_id')) && old('item_lineup_id') == $id) selected @elseif(isset($product) && $product->item_lineup_id == $id) selected @endif>{{$name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{$errors->first('item_lineup_id')}}</div>
                    </div>
                </div>
            </div>

            {{-- 対応SKU --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.product.sku')}}</label>
                    <div class="col-9">
                        <select name="stock_keeping_unit_id[]" id="stock_keeping_unit_id" class="form-control form-control-sm @isInvalid($errors,'stock_keeping_unit_id')" required multiple
                            v-model="stock_keeping_unit_id_selected"
                            data-value="{{ json_encode(!is_null(old('stock_keeping_unit_id')) ? old('stock_keeping_unit_id') : (isset($stock_keeping_unit_id) ? $stock_keeping_unit_id->toArray() : [])) }}">
                            @foreach($stockKeepingUnitList as $id => $name)
                                <option value="{{$id}}">{{$name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{$errors->first('stock_keeping_unit_id')}}</div>
                    </div>
                </div>
            </div>

            {{-- 本数 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.product.volume')}}</label>
                    <div class="col-9">
                        <select name="product_volume" class="form-control form-control-sm  @isInvalid($errors,'product_volume')" required>
                            <option value=""></option>
                            @foreach($productVolumeList as $id => $name)
                                <option value="{{$id}}" @if(!is_null(old('product_volume')) && old('product_volume') == $id) selected @elseif (isset($product) && $product->volume == $id) selected @endif>{{$name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{$errors->first('product_volume')}}</div>
                    </div>
                </div>
            </div>

            {{-- 顧客 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.product.target_customer')}}</label>
                    <div class="col-9">
                        <select name="sales_target_id" class="form-control form-control-sm  @isInvalid($errors,'sales_target_id')" required>
                            <option value=""></option>
                            @foreach($SalesTargetList as $id => $name)
                                <option value="{{$id}}" @if(!is_null(old('sales_target_id')) && old('sales_target_id') == $id) selected @elseif (isset($product) && $product->sales_target_id == $id) selected @endif>{{$name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{$errors->first('sales_target_id')}}</div>
                    </div>
                </div>
            </div>

            {{-- 販売経路 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.product.sales_route')}}</label>
                    <div class="col-9">
                        <select name="sales_route_id" class="form-control form-control-sm @isInvalid($errors,'sales_route_id')" required>
                            <option value=""></option>
                            @foreach($salesRouteList as $id => $name)
                                <option value="{{$id}}" @if(!is_null(old('sales_route_id')) && old('sales_route_id') == $id) selected @elseif (isset($product) && $product->sales_route_id == $id) selected @endif>{{$name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{$errors->first('sales_route_id')}}</div>
                    </div>
                </div>
            </div>

            {{-- 未発送集計区分 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__("admin.item_name.product.undelivered_summary_classification")}}</label>
                    <div class="col-9">
                        <select name="undelivered_summary_classification_id" id="undelivered_summary_classification_id" class="form-control form-control-sm @isInvalid($errors,'undelivered_summary_classification_id')" required
                                v-model="undelivered_summary_classification_selected" @change="undelivered_summary_classification_changed"
                                data-value="{{ !is_null(old('undelivered_summary_classification_id')) ? old('undelivered_summary_classification_id') : (isset($product) ? $product->undelivered_summary_classification_id : 1) }}">
                            @foreach($undeliveredSummaryClassificationList as $id=>$name)
                                <option value="{{$id}}">{{$name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{$errors->first('undelivered_summary_classification_id')}}</div>
                    </div>
                </div>
            </div>

            {{-- マーケティング集計区分 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__("admin.item_name.product.marketing_summary_classification")}}</label>
                    <div class="col-9">
                        <select name="marketing_summary_classification_id" id="marketing_summary_classification_id" class="form-control form-control-sm @isInvalid($errors,'marketing_summary_classification_id')" id="marketing_summary_classification_id" required
                                v-model="marketing_summary_classification_selected" @change="marketing_summary_classification_changed"
                                data-value="{{ !is_null(old('marketing_summary_classification_id')) ? old('marketing_summary_classification_id') : (isset($marketing_summary_classification_id) ? $marketing_summary_classification_id : 1) }}">
                            @foreach($marketingSummaryClassificationList as $id=>$name)
                                <option :disabled="undelivered_summary_classification_selected != 1 && marketing_summary_classification_selected != {{$id}}" value="{{$id}}">{{$name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{$errors->first('marketing_summary_classification_id')}}</div>
                    </div>
                </div>
            </div>


            {{-- 公開/非公開 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.product.visible')}}</label>
                    <div class="col-9">
                        <select name="user_visible" class="form-control form-control-sm @isInvalid($errors,'user_visible')" required>
                            @foreach($productVisibleList as $id=>$name)
                                <option value="{{$id}}" @if (!is_null(old('user_visible')) && old('user_visible') == $id) selected @elseif (isset($product) && $product->user_visible == $id) selected @endif>{{$name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{$errors->first('user_visible')}}</div>
                    </div>
                </div>
            </div>


            {{-- 二回目以降値引きリセット --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">
                        {{__('admin.item_name.product.periodic_batch_discount_to_zero_flag')}}
                    </label>
                    <div class="col-9">
                        <select name="periodic_batch_discount_to_zero_flag" id="periodic_batch_discount_to_zero_flag" class="form-control form-control-sm @isInvalid($errors,'periodic_batch_discount_to_zero_flag')" required
                                v-model="periodic_batch_discount_to_zero_flag_selected"
                                data-value="{{ !is_null(old('periodic_batch_discount_to_zero_flag')) ? old('periodic_batch_discount_to_zero_flag') : (isset($product) ? ($product->periodic_batch_discount_to_zero_flag ? 1 : 0) : 0) }}">
                            <option :disabled="marketing_summary_classification_selected != 3 && marketing_summary_classification_selected != 4 && periodic_batch_discount_to_zero_flag_selected != 0" value="0">{{__('admin.item_name.product.enabled_false')}}</option>
                            <option :disabled="marketing_summary_classification_selected != 3 && marketing_summary_classification_selected != 4 && periodic_batch_discount_to_zero_flag_selected != 1" value="1">{{__('admin.item_name.product.enabled_true')}}</option>
                        </select>
                        <div class="invalid-feedback">{{$errors->first('periodic_batch_discount_to_zero_flag')}}</div>
                    </div>
                </div>
            </div>

            {{-- 初回値引き額 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">
                        {{__('admin.item_name.product.periodic_first_nebiki')}}
                    </label>
                    <div class="col-9">
                        <input name="periodic_first_nebiki" id="periodic_first_nebiki" type="number" min="0" max="2147483647" class="form-control form-control-sm @isInvalid($errors,'periodic_first_nebiki')"  onchange="return app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);"
                               :disabled="marketing_summary_classification_selected != 3 && marketing_summary_classification_selected != 4" v-model="periodic_first_nebiki_selected"
                               data-value="{{ !is_null(old('periodic_first_nebiki')) ? old('periodic_first_nebiki') : (isset($product) ? $product->periodic_first_nebiki : '') }}">
                        <div class="invalid-feedback">{{$errors->first('periodic_first_nebiki')}}</div>
                    </div>
                </div>
            </div>

            {{-- 販売価格 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm" >{{__('admin.item_name.product.price')}}</label>
                    <div class="col-9">
                        <div class="input-group-prepend">
                            <input name="product_price" type="number" min="0" max="2147483647" class="form-control form-control-sm @isInvalid($errors,'product_price')" onchange="return app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);" required
                                   value="@if(!is_null(old('product_price'))){{old('product_price')}}@elseif(isset($product)){{$product->price}}@endif">
                            <span class="input-group-text form-control-sm">{{__('admin.item_name.common.wen')}}</span>
                            <div class="invalid-feedback">{{$errors->first('product_price')}}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 定価 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm" >{{__('admin.item_name.product.catalog_price')}}</label>
                    <div class="col-9">
                        <div class="input-group-prepend">
                            <input name="product_catalog_price" type="number" min="0" max="2147483647" class="form-control form-control-sm @isInvalid($errors,'product_catalog_price')" onchange="return app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);" required
                                   value="@if(!is_null(old('product_catalog_price'))){{old('product_catalog_price')}}@elseif(isset($product)){{$product->catalog_price}}@endif">
                            <span class="input-group-text form-control-sm">{{__('admin.item_name.common.wen')}}</span>
                            <div class="invalid-feedback">{{$errors->first('product_catalog_price')}}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 購入制限/回 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm" >{{__('admin.item_name.product.limit_once')}}</label>
                    <div class="col-9">
                        <div class="input-group-prepend">
                            <span class="input-group-text form-control-sm">{{__('admin.item_name.product.sale_limit_at_once_from')}}</span>
                            <input name="sale_limit_at_once" type="number" class="form-control form-control-sm @isInvalid($errors,'sale_limit_at_once')" onchange="return app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);" min="1" max="32767"
                                   value="@if(!is_null(old('sale_limit_at_once'))){{old('sale_limit_at_once')}}@elseif(isset($product)){{$product->sale_limit_at_once}}@endif">
                            <span class="input-group-text form-control-sm">{{__('admin.item_name.product.sale_limit_at_once_to')}}</span>
                            <div class="invalid-feedback">{{$errors->first('sale_limit_at_once')}}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 購入制限/計 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm" >{{__('admin.item_name.product.limit_total')}}</label>
                    <div class="col-9">
                        <div class="input-group-prepend">
                            <span class="input-group-text form-control-sm">{{__('admin.item_name.product.sale_limit_for_one_customer_from')}}</span>
                            <input name="sale_limit_for_one_customer" type="number" class="form-control form-control-sm @isInvalid($errors,'sale_limit_for_one_customer')" onchange="return app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);" min="1" max="32767"
                                   value="@if(!is_null(old('sale_limit_for_one_customer'))){{old('sale_limit_for_one_customer')}}@elseif(isset($product)){{$product->sale_limit_for_one_customer}}@endif">
                            <span class="input-group-text form-control-sm">{{__('admin.item_name.product.sale_limit_for_one_customer_to')}}</span>
                            <div class="invalid-feedback">{{$errors->first('sale_limit_for_one_customer')}}</div>
                        </div>
                        <small class="form-text text-muted">{{__("admin.help_text.product.same_customer")}}</small>
                    </div>
                </div>
            </div>

            {{-- 購入制限/ --}}


            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.product.product_purchase_warnings_type1')}}</label>
                    <div class="col-9">
                        <select name="product_purchase_warnings_type1[]" id="product_purchase_warnings_type1" class="form-control form-control-sm @isInvalid($errors,'product_purchase_warnings_type1')" multiple
                                v-model="product_purchase_warnings_type1_selected"
                                data-value="{{ json_encode(!is_null(old('product_purchase_warnings_type1')) ? old('product_purchase_warnings_type1') : (isset($product_purchase_warnings_type1) ? $product_purchase_warnings_type1->toArray() : [])) }}">
                            @foreach($productCodeList as $id => $name)
                                <option value="{{$id}}">{{$name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{$errors->first('product_purchase_warnings_type1')}}</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.product.product_purchase_warnings_type2')}}</label>
                    <div class="col-9">
                        <select name="product_purchase_warnings_type2[]" id="product_purchase_warnings_type2"  class="form-control form-control-sm @isInvalid($errors,'product_purchase_warnings_type2')" multiple
                                v-model="product_purchase_warnings_type2_selected"
                                data-value="{{ json_encode(!is_null(old('product_purchase_warnings_type2')) ? old('product_purchase_warnings_type2') : (isset($product_purchase_warnings_type2) ? $product_purchase_warnings_type2->toArray() : [])) }}">
                            @foreach($productCodeList as $id => $name)
                                <option value="{{$id}}">{{$name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{$errors->first('product_purchase_warnings_type2')}}</div>
                    </div>
                </div>
            </div>

            {{-- 支払方法固定 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.product.fixed_payment_method_id')}}</label>
                    <div class="col-9">
                        <select name="fixed_payment_method_id" class="form-control form-control-sm @isInvalid($errors,'fixed_payment_method_id')">
                                <option value=""></option>
                            @foreach($paymentList as $id => $name)
                                <option value="{{$id}}" @if (!is_null(old('fixed_payment_method_id')) && old('fixed_payment_method_id') == $id) selected @elseif(isset($product) && ($product->fixed_payment_method_id == $id)) selected @endif>{{$name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{$errors->first('fixed_payment_method_id')}}</div>
                    </div>
                </div>
            </div>

            {{-- 定期間隔固定 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.product.fixed_periodic_interval_id')}}</label>
                    <div class="col-9">
                        <select name="fixed_periodic_interval_id" id="fixed_periodic_interval_id" class="form-control form-control-sm @isInvalid($errors,'fixed_periodic_interval_id')"
                                :disabled="marketing_summary_classification_selected != 3  && marketing_summary_classification_selected != 4" v-model="fixed_periodic_interval_id_selected"
                                data-value="{{ !is_null(old('fixed_periodic_interval_id')) ? old('fixed_periodic_interval_id') : (isset($product) ? $product->fixed_periodic_interval_id : '') }}">
                            <option value=""></option>
                            @foreach($fixedPeriodicIntervalList as $id => $periodic_interval_type_id)
                                <option value="{{$id}}">{{$periodicIntervalTypeList[$periodic_interval_type_id]}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{$errors->first('fixed_periodic_interval_id')}}</div>
                    </div>
                </div>
            </div>

            {{-- 商品画像 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm" >{{__('admin.item_name.product.image')}}</label>
                    <div class="col-9">
                        <input type="file" name="product_logo" accept=".bmp, .jpg, .png" @if(isset($product) && isset($product->id)) @else required @endif value="@if (!is_null(old('product_logo'))){{old('product_logo')}}@endif">
                        <small class="form-text text-muted">{{__("admin.help_text.product.image_pixel")}}</small>
                    </div>
                </div>
            </div>

            {{-- 備考欄 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm" >{{__('admin.item_name.product.remark')}}</label>
                    <div class="col-9">
                        <textarea class="form-control form-control-sm" rows="3"></textarea>

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

@push('content_js')
    <script>
        var stock_keeping_unit_id = document.getElementById('stock_keeping_unit_id').dataset.value;
        stock_keeping_unit_id = stock_keeping_unit_id ? JSON.parse(stock_keeping_unit_id) : [];
        var product_purchase_warnings_type1 = document.getElementById('product_purchase_warnings_type1').dataset.value;
        product_purchase_warnings_type1 = product_purchase_warnings_type1 ? JSON.parse(product_purchase_warnings_type1) : [];
        var product_purchase_warnings_type2 = document.getElementById('product_purchase_warnings_type2').dataset.value;
        product_purchase_warnings_type2 = product_purchase_warnings_type2 ? JSON.parse(product_purchase_warnings_type2) : [];

        new Vue({
            el: '#edit-product-body',
            data: {
                undelivered_summary_classification_selected: document.getElementById('undelivered_summary_classification_id').dataset.value,
                marketing_summary_classification_selected: document.getElementById('marketing_summary_classification_id').dataset.value,
                periodic_batch_discount_to_zero_flag_selected : document.getElementById('periodic_batch_discount_to_zero_flag').dataset.value,
                fixed_periodic_interval_id_selected: document.getElementById('fixed_periodic_interval_id').dataset.value,
                periodic_first_nebiki_selected: document.getElementById('periodic_first_nebiki').dataset.value,
                stock_keeping_unit_id_selected: stock_keeping_unit_id,
                product_purchase_warnings_type1_selected: product_purchase_warnings_type1,
                product_purchase_warnings_type2_selected: product_purchase_warnings_type2,
            },
            methods: {
                undelivered_summary_classification_changed: function (event) {
                    if (event) {
                        if (event.target.value != 1) {
                            this.marketing_summary_classification_selected = 1;
                            this.periodic_batch_discount_to_zero_flag_selected = 0;
                        }
                    }
                },
                marketing_summary_classification_changed: function (event) {
                    if (event) {
                        if (event.target.value != 1 || event.target.value != 2) {
                            this.periodic_batch_discount_to_zero_flag_selected = 0;
                            this.fixed_periodic_interval_id_selected = '';
                            this.periodic_first_nebiki_selected = '';
                        }
                    }
                }
            }
        })
    </script>
@endpush