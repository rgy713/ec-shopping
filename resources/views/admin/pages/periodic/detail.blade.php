{{-- 定期詳細、通常画面とポップアップ、異なるレイアウトで同じコンテンツを表示するため、contensセクションはinclude --}}
@extends('admin.layouts.main.contents')
@section('title') 受注管理 > 定期登録 @endsection

@section('contents')
    <div class="row">
        {{-- お客様情報 start --}}
        <div class="col-12">
            @include('admin.components.periodic.edit_basic_info_form',['prefix'=>'delivery'])
        </div>

        <span class="specification" data-toggle="popover" data-placement="right" data-content="カード決済の情報は、支払い方法の下部に表示">&nbsp;</span>

        {{-- お客様情報 start --}}
        <div class="col-12">
            @include('admin.components.order.edit_order_customer_info_form',['prefix'=>'delivery'])
        </div>
        {{-- お客様情報 end --}}

        {{-- 受注商品情報 start --}}
        <div class="col-12">
            @include('admin.components.order.edit_order_item_info_form',['prefix'=>'delivery'])
        </div>
        {{-- 受注商品情報 end --}}

        {{-- 配送/支払い方法 start --}}
        <div class="col-12">
            @include('admin.components.order.edit_order_delivery_info_form',['prefix'=>'delivery'])
        </div>
        {{-- 配送/支払い方法 end --}}

        <div class="col-12">
            @include("admin.components.common.edit_form_button")
        </div>
    </div>

@endsection
