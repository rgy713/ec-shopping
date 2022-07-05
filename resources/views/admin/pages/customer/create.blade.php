@extends('admin.layouts.main.contents')

@section('title') {{__('admin.page_title.customer_create')}} @endsection

@section('contents')
<form class="form-horizontal" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="row">
        {{-- 顧客情報 start --}}
        <div class="col-12">
            @include('admin.components.customer.edit_form',['mode'=>'create'])
        </div>
        {{-- 顧客情報 end --}}

        {{-- shop memo start --}}
        <div class="col-12">
            @include('admin.components.common.shop_memo_input',['shopMemoTitle'=>"Shopメモ",'withShopMemoInput'=>true,'withShopMemoList'=>false,'shopMemoHeight'=>'186px'])
        </div>
        {{-- shop memo end --}}

        {{-- ボタン --}}
        <div class="col-12">
            <div class="row mb-3">
                <div class="col-6 offset-3">
                    <button class="btn btn-sm btn-block btn-primary" type="submit">
                        <i class="{{__('admin.icon_class.save')}}"></i>&nbsp;{{__('admin.operation.save')}}
                    </button>
                </div>
            </div>
        </div>
        {{-- ボタン --}}
    </div>
</form>

@endsection

