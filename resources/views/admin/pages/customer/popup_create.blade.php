{{-- 受注詳細、通常画面とポップアップ、異なるレイアウトで同じコンテンツを表示するため、contensセクションはinclude --}}
@extends('admin.layouts.popup.contents')

@section('title') {{__('admin.page_title.customer_create')}} @endsection

@section('contents')
        <div class="row" id="customer_info_body">
            <form id="edit_form" class="form-horizontal" method="post" action="{{route('admin.customer.create_save')}}" @submit.prevent="onSubmit">
                {{csrf_field()}}
                <input type="hidden" name="isPopup" value="true">

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
            </form>
        </div>

@endsection

@push('content_js')
    <script>
        new Vue({
            el: '#customer_info_body',

            methods: {
                onSubmit:function() {
                    const form_action = $("#edit_form").attr("action");
                    const data_form = new FormData($("#edit_form")[0]);
                    axios.post(form_action, data_form)
                        .then(response => {
                            if (response.data && response.data.status == 'success') {
                                localStorage.setItem("new_customer", response.data.saved);
                                window.close();
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