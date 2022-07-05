{{-- 受注詳細、通常画面とポップアップ、異なるレイアウトで同じコンテンツを表示するため、contensセクションはinclude --}}
@extends('admin.layouts.main.contents')

@section('title') {{__("admin.page_title.order_edit")}} @endsection

@section('contents')
    <form method="POST" id="edit_basic_info_form" accept-charset="UTF-8" action="{{route("admin.order.edit_info")}}">
        {{ csrf_field() }}
        <input type="hidden" name="update_type">
        <input type="hidden" name="updated_at" value="@if(isset($order) && isset($order['updated_at'])){{$order['updated_at']}}@endif">
        <input name="isShipping" type="hidden" value="@if(isset($order) && isset($order['isShipping'])){{$order['isShipping']}}@endif">
        <div class="row" id="order_info_body">
            <div class="col-12">
                @include('admin.components.order.edit_basic_info_form')
            </div>

            {{-- 受注補足情報 start --}}
            <div class="col-12">
                @include('admin.components.order.display_additional_order_info')
            </div>
            {{-- 受注補足情報 end --}}

            {{-- 決済情報 start --}}
            @if(isset($order) && isset($order['payment_method_id']) && $order['payment_method_id'] == 5)
                <div class="col-12" id="edit_payment_detail_info_form">
                    @include('admin.components.order.payment_detail_info')
                </div>
            @endif
            {{-- 決済情報 end --}}

            {{-- お客様情報 start --}}
            <div class="col-12">
                @include('admin.components.order.edit_order_customer_info_form')
            </div>
            {{-- お客様情報 end --}}

            {{-- 受注商品情報 start --}}
            <div class="col-12" id="edit_order_item_info_form">
                @include('admin.components.order.edit_order_item_info_form')
            </div>
            {{-- 受注商品情報 end --}}

            {{-- 配送/支払い方法 start --}}
            <div class="col-12" id="edit_order_delivery_info_form">
                @include('admin.components.order.edit_order_delivery_info_form')
            </div>
            {{-- 配送/支払い方法 end --}}

            {{-- shop memo start --}}
            <div class="col-6" id="shop_memo_info_order">
                @include('admin.components.common.shop_memo_input_order',['shopMemos'=>isset($orderShopMemos) ? $orderShopMemos : null, 'prefix'=>'order', 'shopMemoTitle'=>__("admin.item_name.order.order_memo"),'withShopMemoInput'=>true,'withShopMemoList'=>true,'shopMemoHeight'=>'186px'])
            </div>
            <div class="col-6" id="shop_memo_info_customer">
                @include('admin.components.common.shop_memo_input_order',['shopMemos'=>isset($customerShopMemos) ? $customerShopMemos : null, 'prefix'=>'customer', 'shopMemoTitle'=>__("admin.item_name.order.customer_memo"),'withShopMemoInput'=>false,'withShopMemoList'=>true,'shopMemoHeight'=>'320px'])
            </div>

            {{-- ボタン --}}
            <div class="col-12">
                <div class="row mb-3">
                    <div class="col-6">
                        <a class="btn btn-sm btn-block btn-secondary" tabindex="-1" href="@if(isset($order) && isset($order['isShipping'])){{route('admin.order.shipping', ['back'=>true])}}@else{{route('admin.order.search', ['back'=>true])}}@endif">
                            <i class="{{__('admin.icon_class.back')}}"></i>&nbsp;{{__('admin.operation.back')}}
                        </a>
                    </div>

                    <div class="col-6">
                        <button class="btn btn-sm btn-block btn-primary" type="submit">
                            <i class="{{__('admin.icon_class.confirm')}}"></i>&nbsp;{{__('admin.operation.confirm')}}
                        </button>
                    </div>
                </div>
            </div>
            {{-- ボタン --}}

        </div>
    </form>

    <form id="product_search_form" method="POST" accept-charset="UTF-8" action="{{route('admin.product.search_result_modal')}}">
        {{ csrf_field() }}
    </form>
    <form id="product_summary_form" method="POST" accept-charset="UTF-8" action="{{route('admin.order.product_summary')}}">
        {{ csrf_field() }}
    </form>
    <form id="sendTelegramCreditCommitRevise_form" method="POST" accept-charset="UTF-8" action="{{route('admin.order.sendTelegramCreditCommitRevise')}}">
        {{ csrf_field() }}
    </form>
    <form id="sendTelegramCreditCommitCancel_form" method="POST" accept-charset="UTF-8" action="{{route('admin.order.sendTelegramCreditCommitCancel')}}">
        {{ csrf_field() }}
    </form>
    <form id="sendTelegramCreditStockDelete_form" method="POST" accept-charset="UTF-8" action="{{route('admin.order.sendTelegramCreditStockDelete')}}">
        {{ csrf_field() }}
    </form>
    <form id="get_settlement_cards_form" action="{{route('admin.order.payment.settlements')}}" method="POST" accept-charset="UTF-8">
        {{ csrf_field() }}
    </form>
    <form id="delivery_pdf_form" method="POST" accept-charset="UTF-8" action={{ route('admin.order.delivery_pdf') }}>
        {{ csrf_field() }}
    </form>
@endsection