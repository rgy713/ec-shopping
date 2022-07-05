@inject('itemLineupList', 'App\Common\KeyValueLists\ItemLineupList')
@inject('marketingSummaryClassificationList', 'App\Common\KeyValueLists\MarketingSummaryClassificationList')

{{-- 受注商品情報：受注、定期で共通 --}}
<div class="card">
    <div class="card-header">
        {{__("admin.page_header_name.order_product_create")}}
        <div class="card-header-actions">

        </div>
    </div>

    <div class="card-body">
        <div class="row">
            @if (isset($order) && isset($order['order_id']))
                <input type="hidden" name="order_summary_disable" value="@if (isset($order) && isset($order['order_summary_disable'])){{$order['order_summary_disable']}}@endif">
                @if ($isPeriodic == false)
                    <input type="hidden" name="tax_rate" value="@if (isset($order) && isset($order['tax_rate'])){{$order['tax_rate']}}@endif">
                @endif
            @endif
            {{-- 商品表示エリア --}}
            <div class="col-12 col-lg-8">
                <table id="select_products_table" class="table table-sm table-bordered"
                    data-value="">

                    <tr>
                        <th>{{__("admin.item_name.product.code")}}</th>
                        <th>{{__("admin.item_name.product.name")}}</th>
                        <th>{{__("admin.item_name.product.price")}}</th>
                        <th>{{__("admin.item_name.order.quantity")}}</th>
                        <th>{{__("admin.item_name.order.subtotal")}}</th>
                    </tr>

                    @if(isset($order) && isset($order['selected_product_ids']))
                        @foreach($order['selected_product_ids'] as $product)
                            <tr>
                                <input type="hidden" name="selected_product_ids[]" value="{{$product->id}}">
                                <td>{{ $product->code }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->price }}{{__("admin.item_name.common.wen")}}</td>
                                <td>
                                    {{ $product->quantity }}
                                    <input type="hidden" name="product_quantity[{{$product->id}}]" value="{{ $product->quantity }}">
                                </td>
                                <td class="text-right">{{ $product->subtotal }}{{__("admin.item_name.common.wen")}}</td>
                            </tr>
                        @endforeach
                    @endif

                    <tfoot>
                    <tr>
                        <td colspan="6" class="text-right">
                            @if(isset($order) && isset($order['order_subtotal'])){{$order['order_subtotal']}}@endif{{__("admin.item_name.common.wen")}}
                            <input type="hidden" name="order_subtotal"
                                   value="@if(isset($order) && isset($order['order_subtotal'])){{$order['order_subtotal']}}@endif">
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
                            <div class="col-sm-9">
                                @if(isset($order) && isset($order['order_delivery_fee'])){{$order['order_delivery_fee']}}{{__("admin.item_name.common.wen")}}@endif
                                <input name="order_delivery_fee" type="hidden"
                                       value="@if(isset($order) && isset($order['order_delivery_fee'])){{$order['order_delivery_fee']}}@endif">
                            </div>
                        </div>
                    </div>

                    {{-- 支払手数料 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-sm-3 col-form-label form-control-sm" >{{__("admin.item_name.order.payment_fee")}}</label>
                            <div class="col-sm-9">
                                @if(isset($order) && isset($order['order_payment_method_fee'])){{$order['order_payment_method_fee']}}{{__("admin.item_name.common.wen")}}@endif
                                <input name="order_payment_method_fee" type="hidden"
                                       value="@if(isset($order) && isset($order['order_payment_method_fee'])){{$order['order_payment_method_fee']}}@endif">
                            </div>
                        </div>
                    </div>

                    {{-- 値引き金額 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-sm-3 col-form-label form-control-sm" >{{__("admin.item_name.order.discount")}}</label>
                            <div class="col-sm-9">
                                @if(isset($order) && isset($order['order_discount'])){{$order['order_discount']}}{{__("admin.item_name.common.wen")}}@endif
                                <input name="order_discount" type="hidden"
                                       value="@if(isset($order) && isset($order['order_discount'])){{$order['order_discount']}}@endif">
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
                        @if(isset($order) && isset($order['order_payment_total'])){{$order['order_payment_total']}}{{__("admin.item_name.common.wen")}}@endif
                        <input name="order_payment_total" type="hidden"
                               value="@if(isset($order) && isset($order['order_payment_total'])){{$order['order_payment_total']}}@endif">
                    </div>
                </div>
            </div>
            {{-- 消費税 --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="offset-lg-8 col-3 col-lg-1 col-form-label form-control-sm" >{{__("admin.item_name.order.tax")}}</label>
                    <div class="col-9 col-lg-3">
                        @if(isset($order) && isset($order['order_payment_total_tax'])){{$order['order_payment_total_tax']}}{{__("admin.item_name.common.wen")}}@endif
                        <input name="order_payment_total_tax" type="hidden"
                               value="@if(isset($order) && isset($order['order_payment_total_tax'])){{$order['order_payment_total_tax']}}@endif">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
