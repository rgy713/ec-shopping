@extends('admin.layouts.main.contents')

@section('title') {{__('admin.page_title.customer_information')}} @endsection

@push('content_css')
    <style>
        .col-xl-4, .col-xl-6, .col-xl-8 {
            padding-left: 2px;
            padding-right: 2px;
        }

        .card {
            margin-bottom: 4px;
        }

        .data-spy {
            padding-right: 10px;
        }
    </style>
@endpush

@section('contents')

    <div class="row">
        {{-- 顧客情報 start --}}
        <div class="col-xl-4" style="margin-bottom: 4px;">
            <form id="customer_edit_form" class="form-horizontal" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" name="updated_at" value="{{$customer->updated_at}}">
                <div class="row">
                    <div class="col-12">
                        @include('admin.components.customer.edit_form',['mode'=>'edit', 'admin'=>$admin])
                    </div>

                </div>
            </form>
        </div>
        {{-- 顧客情報 end --}}


        <div class="col-xl-8">
            {{-- 受注状況 start --}}
            @include('admin.components.order.order_list_of_customer',['orders'=>$customer->orders()->orderBy('id','DESC')->get()])
            {{-- 受注状況 end --}}

            {{-- 定期状況 start --}}
            @include('admin.components.periodic.order_list_of_customer',['periodicOrders'=>$customer->periodicOrders()->orderBy('id','DESC')->get()])
            {{-- 定期状況 end --}}

            {{-- 添付一覧 start --}}
            @include('admin.components.customer.attachments', ['attachments'=>$customer->attachments()->orderBy('id','DESC')->get()])
            {{-- 添付一覧 end --}}
        </div>


        <div class="col-xl-6">
            {{-- shopメモ start --}}
            <form id="customer_edit_shop_memo_form" class="form-horizontal">
                @include('admin.components.common.shop_memo_input',['shopMemoTitle'=>"Shopメモ",'withShopMemoInput'=>true,'withShopMemoList'=>true,'shopMemoHeight'=>'186px', 'shopMemos'=>$customer->shopMemos()->orderBy('important','DESC')->orderBy('claim_flag','DESC')->orderBy('created_at','DESC')->get()])
            </form>
            {{-- shopメモ end --}}
        </div>

        <div class="col-xl-6">
            {{-- shopメモ start --}}
            @include('admin.components.common.shop_memo_input',['shopMemoTitle'=>"クレーム一覧",'withShopMemoInput'=>false,'withShopMemoList'=>true,'shopMemoHeight'=>'320px', 'shopMemos'=>$customer->shopMemos()->where('claim_flag',TRUE)->orderBy('created_at','DESC')->get()])
            {{-- shopメモ end --}}
        </div>

        <div class="col-12 mb-3 mt-3">
            <div class="row">
                <div class="col-6">
                    <a href="{{route("admin.customer.info.back")}}">
                        <button class="btn btn-sm btn-block btn-secondary" tabindex="-1" type="button">{{__('admin.operation.back')}}</button>
                    </a>
                </div>
                <div class="col-6">
                    <button class="btn btn-sm btn-block btn-primary" onclick="customerUpdate();">{{__('admin.operation.save')}}</button>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('content_js')
    <script>
        function customerUpdate()
        {
            const $customer_form = $("form#customer_edit_form");
            $("form#customer_edit_shop_memo_form :input").not(':submit').clone().hide().appendTo("form#customer_edit_form");
            $customer_form.submit();
        }

        function PopupWindow(url){
            var PopupWindow=window.open(url,'PopupWindow',width=600,height=300);
            PopupWindow.onbeforeunload = function(){
                window.location.reload();
            };
            return false;
        }

    </script>
@endpush