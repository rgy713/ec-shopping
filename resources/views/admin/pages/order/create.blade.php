{{-- 受注詳細、通常画面とポップアップ、異なるレイアウトで同じコンテンツを表示するため、contensセクションはinclude --}}
@extends('admin.layouts.main.contents')

@section('title') {{__("admin.page_title.order_create")}} @endsection

@section('contents')
    <form method="POST" id="edit_basic_info_form" accept-charset="UTF-8" action="{{route("admin.order.create_info")}}">
        {{ csrf_field() }}
        <div class="row" id="order_info_body">
            <div class="col-12">
                @include('admin.components.order.edit_basic_info_form')
            </div>

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
                @include('admin.components.common.shop_memo_input_order',['prefix'=>'order', 'shopMemoTitle'=>__("admin.item_name.order.order_memo"),'withShopMemoInput'=>true,'withShopMemoList'=>false,'shopMemoHeight'=>'186px'])
            </div>
            <div class="col-6" id="shop_memo_info_customer">
                @include('admin.components.common.shop_memo_input_order',['shopMemos'=>isset($customerShopMemos) ? $customerShopMemos : null, 'prefix'=>'customer', 'shopMemoTitle'=>__("admin.item_name.order.customer_memo"),'withShopMemoInput'=>false,'withShopMemoList'=>true,'shopMemoHeight'=>'320px'])
            </div>

            {{-- shop memo end --}}


            {{-- ボタン --}}
            <div class="col-12">
                <div class="row mb-3">
                    <div class="col-6">
                        <a class="btn btn-sm btn-block btn-secondary" tabindex="-1" href="@if(isset($order) && isset($order['old_customer_id'])){{route('admin.customer.info', ['back'=>true])}}@else{{route('admin.order.search', ['back'=>true])}}@endif">
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
@endsection
