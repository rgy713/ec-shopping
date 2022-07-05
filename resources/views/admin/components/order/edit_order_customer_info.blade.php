@inject('mediaCodeList', 'App\Common\KeyValueLists\MediaCodeList')

{{-- 受注者情報エリア：受注、定期で共通 --}}
<div class="card">
    <div class="card-header">
        {{__("admin.page_header_name.order_customer")}}
        <div class="card-header-actions">

        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="row">
                    {{-- 顧客ID --}}
                    <div class="col-12">
                        <div class="row form-group">
                            <label class="col-2 col-lg-2 col-form-label form-control-sm">{{__("admin.item_name.customer.id")}}</label>
                            <div class="col-10 col-lg-10">
                                <input name="customer_id" type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if(isset($order) && isset($order['customer_id'])){{$order['customer_id']}}@endif">
                            </div>
                        </div>
                    </div>
                    {{-- メールアドレス --}}
                    <div class="col-12">
                        <div class="row form-group">
                            <label class="col-2 col-lg-2 col-form-label form-control-sm">{{__("admin.item_name.customer.email")}}</label>
                            <div class="col-10 col-lg-10">
                                <input name="customer_email" type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if(isset($order) && isset($order['customer_email'])){{$order['customer_email']}}@endif">
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="row form-group">
                            <label class="col-2 col-lg-2 col-form-label form-control-sm" >{{__("admin.item_name.media.code")}}</label>
                            <div class="col-10 col-lg-10">
                                <input name="customer_email" type="text" readonly class="form-control-plaintext form-control-sm"
                                       value="@if(isset($order) && isset($order['customer_advertising_media_code'])){{$order['customer_advertising_media_code']}}@endif">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="row">
                    @include('admin.components.common.address_info',['prefix'=>'customer'])
                </div>
            </div>
        </div>
    </div>
</div>