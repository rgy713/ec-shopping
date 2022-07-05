@inject('itemLineupList', 'App\Common\KeyValueLists\ItemLineupList')
@inject('marketingSummaryClassificationList', 'App\Common\KeyValueLists\MarketingSummaryClassificationList')

@php
    $curYear = (int)date('Y');
    $curMonth = (int)date('m');
    $firstDayOfCurMonth = date("Y-m-d H:i", mktime(0, 0, 0, $curMonth , 1, $curYear));
    $order_summary_disable = isset($order) && isset($order['sales_timestamp']) && date("Y-m-d H:i", strtotime($order['sales_timestamp'])) < $firstDayOfCurMonth
@endphp
{{-- 受注商品情報：受注、定期で共通 --}}
<div class="card">
    <div class="card-header">
        {{__("admin.page_header_name.order_product_create")}}
        <div class="card-header-actions">
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal_order_item_search_form" @click="search_result_new"
                @if($order_summary_disable) disabled @endif>
                <i class="fa fa-shopping-cart"></i>&nbsp;{{__("admin.item_name.order.add_product")}}
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            @if ($mode == 'edit')
                <input type="hidden" name="order_summary_disable" value="{{$order_summary_disable}}">
                <input type="hidden" name="tax_rate" value="@if (isset($order) && isset($order['tax_rate'])){{$order['tax_rate']}}@endif">
            @endif
            {{-- 商品表示エリア --}}
            <div class="col-12 col-lg-8">
                <table id="result_list_table" class="table table-sm table-bordered"
                       data-ids="@if(!is_null(old('selected_product_ids'))){{json_encode(old('selected_product_ids'))}}@elseif(isset($order) && isset($order['selected_product_ids'])){{json_encode($order['selected_product_ids'])}}@endif"
                       data-quantity="@if(!is_null(old('product_quantity'))){{json_encode(old('product_quantity'))}}@elseif(isset($order) && isset($order['product_quantity'])){{json_encode($order['product_quantity'])}}@endif">

                    <tr>
                        <th>{{__("admin.item_name.product.code")}}</th>
                        <th>{{__("admin.item_name.product.name")}}</th>
                        <th></th>
                        <th>{{__("admin.item_name.product.price")}}</th>
                        <th>{{__("admin.item_name.order.quantity")}}</th>
                        <th>{{__("admin.item_name.order.subtotal")}}</th>
                    </tr>

                    <tr v-for="(product, index) in selected_products">
                        <input type="hidden" name="selected_product_ids[]" :value="product.id">
                        <td>@{{ product.code }}</td>
                        <td>@{{ product.name }}</td>
                        <td>
                            <div class="row form-group mb-2 mt-0">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-sm btn-secondary" @click.prevent="remove_product(index)" @if($order_summary_disable) disabled @endif><i class="{{__("admin.icon_class.delete")}}"></i>&nbsp;{{__("admin.operation.delete")}}</button>
                                </div>
                            </div>

                        </td>

                        <td>@{{ product.price }}{{__("admin.item_name.common.wen")}}</td>
                        <td width="30%">
                            <div class="row form-group mb-2 mt-0">
                                <div class="col-sm-6">
                                    <input type="number" @if($order_summary_disable) readonly @endif min="1" max="32767" :name="'product_quantity[' + product.id + ']'" class="form-control form-control-sm text-right" :value="product.quantity ? product.quantity : ''" required   onkeydown="app.functions.only_number_key(event);">
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" @if($order_summary_disable) disabled @endif class="btn btn-sm btn-primary" @click.prevent="product_summary_update(product.id)"><i class="fa fa-refresh"></i>&nbsp;{{__("admin.operation.refresh")}}</button>
                                </div>
                            </div>
                        </td>
                        <td class="text-right">@{{ product.subtotal }}{{__("admin.item_name.common.wen")}}</td>
                    </tr>

                    <tfoot>
                    <tr>
                        <td colspan="6" class="text-right">
                            <input type="hidden" name="order_subtotal" :value="sum_subtotal">
                            @{{sum_subtotal}}{{__("admin.item_name.common.wen")}}
                        </td>
                    </tr>
                    </tfoot>

                </table>
            </div>

            {{-- 受注情報エリア --}}
            <div class="col-12 col-lg-4">
                <div class="row">
                    {{-- 配送料 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-sm-3 col-form-label form-control-sm" >{{__("admin.item_name.order.delivery_fee")}}</label>
                            <div class="col-sm-6">
                                <div class="input-group-prepend">
                                    <input name="order_delivery_fee" @if($order_summary_disable) readonly @endif class="form-control form-control-sm" type="number" min="0" max="2147483647" placeholder="" onchange="return app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);" required
                                           value="@if(!is_null(old('order_delivery_fee'))){{old('order_delivery_fee')}}@elseif(isset($order) && isset($order['order_delivery_fee'])){{$order['order_delivery_fee']}}@else{{0}}@endif">
                                    <span class="input-group-text form-control-sm">{{__("admin.item_name.common.wen")}}</span>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <button type="button" @if($order_summary_disable) disabled @endif class="btn btn-sm btn-primary btn-block" @click.prevent="get_summary()"><i class="fa fa-refresh"></i>&nbsp;{{__("admin.operation.refresh")}}</button>
                            </div>

                        </div>
                    </div>

                    {{-- 支払手数料 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-sm-3 col-form-label form-control-sm" >{{__("admin.item_name.order.payment_fee")}}</label>
                            <div class="col-sm-6">
                                <div class="input-group-prepend">
                                    <input name="order_payment_method_fee" @if($order_summary_disable) readonly @endif class="form-control form-control-sm" type="number" min="0" max="2147483647" placeholder="" onchange="return app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);" required
                                           value="@if(!is_null(old('order_payment_method_fee'))){{old('order_payment_method_fee')}}@elseif(isset($order) && isset($order['order_payment_method_fee'])){{$order['order_payment_method_fee']}}@else{{0}}@endif">
                                    <span class="input-group-text form-control-sm">{{__("admin.item_name.common.wen")}}</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" @if($order_summary_disable) disabled @endif class="btn btn-sm btn-primary btn-block" @click.prevent="get_summary()"><i class="fa fa-refresh"></i>&nbsp;{{__("admin.operation.refresh")}}</button>
                            </div>

                        </div>
                    </div>

                    {{-- 値引き金額 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-sm-3 col-form-label form-control-sm" >{{__("admin.item_name.order.discount")}}</label>
                            <div class="col-sm-6">
                                <div class="input-group-prepend">
                                    <span class="input-group-text form-control-sm"><i class="fa fa-minus"></i></span>
                                    <input name="order_discount" @if($order_summary_disable) readonly @endif class="form-control form-control-sm" type="number" min="0" max="2147483647" placeholder="" onchange="return app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);" required
                                           value="@if(!is_null(old('order_discount'))){{old('order_discount')}}@elseif(isset($order) && isset($order['order_discount'])){{$order['order_discount']}}@else{{0}}@endif">
                                    <span class="input-group-text form-control-sm">{{__("admin.item_name.common.wen")}}</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" @if($order_summary_disable) disabled @endif class="btn btn-sm btn-primary btn-block" @click.prevent="get_summary()"><i class="fa fa-refresh"></i>&nbsp;{{__("admin.operation.refresh")}}</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <div class="row">
            {{-- お支払い合計 --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="offset-lg-8 col-3 col-lg-1 col-form-label form-control-sm" >{{__("admin.item_name.order.payment_total")}}</label>
                    <div class="col-9 col-lg-3">
                        <div class="input-group-prepend">
                            <input name="order_payment_total"  readonly class="form-control form-control-sm"type="number" min="0" max="2147483647" placeholder="" onchange="return app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);"
                                   value="@if(!is_null(old('order_payment_total'))){{old('order_payment_total')}}@elseif(isset($order) && isset($order['order_payment_total'])){{$order['order_payment_total']}}@else{{0}}@endif">
                            <span class="input-group-text form-control-sm">{{__("admin.item_name.common.wen")}}</span>
                        </div>
                    </div>
                </div>
            </div>
            {{-- 消費税 --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="offset-lg-8 col-3 col-lg-1 col-form-label form-control-sm" >{{__("admin.item_name.order.tax")}}</label>
                    <div class="col-9 col-lg-3">
                        <div class="input-group-prepend">
                            <input name="order_payment_total_tax" readonly class="form-control form-control-sm"type="number" min="0" max="2147483647" placeholder="" onchange="return app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);"
                                   value="@if(!is_null(old('order_payment_total_tax'))){{old('order_payment_total_tax')}}@elseif(isset($order) && isset($order['order_payment_total_tax'])){{$order['order_payment_total_tax']}}@else{{0}}@endif">
                            <span class="input-group-text form-control-sm">{{__("admin.item_name.common.wen")}}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('admin.components.product.modal_search')

@push('content_js')
    <script>
        const order_item_vue = new Vue({
            el: '#edit_order_item_info_form',

            data: {
                selected_products: [],
                sum_subtotal: 0,
                show_results: false,
                results: {},
                pagination: {
                    'current_page': 1
                }
            },

            mounted() {
                var old_selected_products = $("#result_list_table").data('ids');
                old_selected_products = old_selected_products ? old_selected_products : [];
                var old_quantity = $("#result_list_table").data('quantity');
                old_quantity = old_quantity ? old_quantity : {};
                this.selected_products = old_selected_products.map((product) => {
                    if (typeof product === "object") {
                        return {id: product.id, quantity: old_quantity[product.id]};
                    }
                    else {
                        return {id: product, quantity: old_quantity[product]};
                    }
                });

                if (this.selected_products.length > 0) {
                    setTimeout(function () {
                        order_item_vue.get_summary(true, true);
                    }, 100);
                }
            },

            methods: {
                search_result_new: function() {
                    app.functions.resetForm('#modal_search_form_body');
                    $('#product_sort_input').val('');

                    this.show_results = false;
                    this.result = {};
                    this.pagination.current_page = 1;
                },
                search_results: function(sort) {
                    this.show_results = true;

                    if (sort) {
                        $('#product_sort_input').val(sort);
                    }
                    const data_form = new FormData($("#product_search_form")[0]);
                    const data_action = $("#product_search_form").attr('action');
                    data_form.append('page', this.pagination.current_page);
                    axios.post(data_action, data_form)
                        .then(response => {
                            this.results = response.data.data.data;
                            this.pagination = response.data.pagination;
                        })
                        .catch(error => {
                            console.log(error.response.data);
                        });
                },
                select_product: function (product_id) {
                    if (!this.selected_products) {
                        this.selected_products = [];
                    }

                    this.selected_products.push({id: product_id, quantity: 1});

                    setTimeout(function () {
                        order_item_vue.get_summary();
                    }, 100);
                },
                product_summary_update: function (product_id) {
                    this.get_summary();
                },
                remove_product: function(index) {
                    this.selected_products.splice(index, 1);

                    setTimeout(function () {
                        order_item_vue.get_summary();
                    }, 100);
                },
                get_summary: function (unnotify, uncalc) {
                    const data_form = new FormData($("#edit_basic_info_form")[0]);
                    if (unnotify) {
                        data_form.set('unnotify', true);
                    }

                    const data_action = $("#product_summary_form").attr('action');

                    axios.post(data_action, data_form)
                        .then(response => {
                            if (response.data && response.data.status == 'success') {
                                var summary = response.data.saved;
                                order_item_vue.selected_products = summary.products ? summary.products : [];
                                order_item_vue.sum_subtotal = summary.sum_subtotal ? summary.sum_subtotal : 0;
                                if (!uncalc) {
                                    $('input[name=order_payment_total]').val(summary.payment_total ? summary.payment_total : 0);
                                    $('input[name=order_payment_total_tax]').val(summary.payment_total_tax ? summary.payment_total_tax : 0);
                                }
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                }
            },
        });
    </script>
@endpush
