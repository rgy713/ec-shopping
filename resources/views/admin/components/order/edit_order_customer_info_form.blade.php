@inject('mediaCodeList', 'App\Common\KeyValueLists\MediaCodeList')

{{-- 受注者情報エリア：受注、定期で共通 --}}
<div class="card">
    <div class="card-header">
        {{__("admin.page_header_name.order_customer")}}
        <div class="card-header-actions">
            @if($mode=="create" && (!isset($order) || !isset($order['old_customer_id'])))
                <button class="btn btn-sm btn-primary" type="button" onclick="popupWindow('{{route("admin.customer.popup.create")}}');"><i class="{{__("admin.icon_class.create")}}"></i>&nbsp;{{__('admin.operation.create')}}</button>
            @endif

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
                                <input name="customer_id" type="hidden"
                                       value="@if(!is_null(old('customer_id'))){{old('customer_id')}}@elseif(isset($order) && isset($order['customer_id'])){{$order['customer_id']}}@endif">
                                <input id="customer_id_input" type="text" readonly class="form-control-plaintext  form-control-sm"
                                       value="@if(!is_null(old('customer_id'))){{old('customer_id')}}@elseif(isset($order) && isset($order['customer_id'])){{$order['customer_id']}}@else{{'('.__("admin.item_name.common.unspecified").')'}}@endif" required>
                            </div>
                        </div>
                    </div>
                    {{-- メールアドレス --}}
                    <div class="col-12">
                        <div class="row form-group">
                            <label class="col-2 col-lg-2 col-form-label form-control-sm">{{__("admin.item_name.customer.email")}}</label>
                            <div class="col-10 col-lg-10">
                                <input name="customer_email" type="text" class="form-control form-control-sm @isInvalid($errors,'customer_email')" placeholder="email@example.com" onchange="return app.functions.trim(this);" required
                                       pattern="^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$" title="{{__('validation.hint_text.email')}}"
                                       value="@if(!is_null(old('customer_email'))){{old('customer_email')}}@elseif(isset($order) && isset($order['customer_email'])){{$order['customer_email']}}@endif">
                                <div class="invalid-feedback">{{$errors->first('customer_email')}}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="row form-group">
                            <label class="col-2 col-lg-2 col-form-label form-control-sm" >{{__("admin.item_name.media.code")}}</label>
                            <div class="col-10 col-lg-10">
                                <input name="customer_advertising_media_code" type="text" class="form-control form-control-sm @isInvalid($errors,'customer_advertising_media_code')" onchange="return app.functions.trim(this);"
                                       pattern="^[0-9]+$" maxlength="7" list="media-code-list"
                                       value="@if(!is_null(old('customer_advertising_media_code'))){{old('customer_advertising_media_code')}}@elseif(isset($order) && isset($order['customer_advertising_media_code'])){{$order['customer_advertising_media_code']}}@endif">
                                <div class="invalid-feedback">{{$errors->first('customer_advertising_media_code')}}</div>
                                <datalist id="media-code-list">
                                    @foreach($mediaCodeList as $key => $item)
                                        <option value="{{$key}}">{{$item}}</option>
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="row">
                    @include('admin.components.common.address_form',['prefix'=>'customer','obj'=>isset($order) ? $order : null])
                </div>
            </div>
        </div>
    </div>
</div>

@push('content_js')
    <script>
        function popupWindow(url){
            var popupWin = window.open(url, 'PopupWindow', width=600, height=300);
            popupWin.onbeforeunload = function(){
                var new_customer = localStorage.getItem("new_customer");
                if (new_customer) {
                    new_customer = JSON.parse(new_customer);
                    $('input[name=customer_id]').val(new_customer.id);
                    $('#customer_id_input').val(new_customer.id);
                    $('input[name=customer_email]').val(new_customer.email);
                    $('input[name=customer_advertising_media_code]').val(new_customer.advertising_media_code);
                    $('input[name=customer_name01]').val(new_customer.name01);
                    $('input[name=customer_name02]').val(new_customer.name02);
                    $('input[name=customer_kana01]').val(new_customer.kana01);
                    $('input[name=customer_kana02]').val(new_customer.kana02);
                    $('input[name=customer_phone_number01]').val(new_customer.phone_number01);
                    $('input[name=customer_phone_number02]').val(new_customer.phone_number02);
                    $('input[name=customer_zipcode]').val(new_customer.zipcode);
                    $('select[name=customer_prefecture]').val(new_customer.prefecture_id);
                    $('input[name=customer_address1]').val(new_customer.address01);
                    $('input[name=customer_address2]').val(new_customer.address02);
                    $('input[name=customer_address3]').val(new_customer.requests_for_delivery);

                    $('input[name=delivery_name01]').val('');
                    $('input[name=delivery_name02]').val('');
                    $('input[name=delivery_kana01]').val('');
                    $('input[name=delivery_kana02]').val('');
                    $('input[name=delivery_phone_number01]').val('');
                    $('input[name=delivery_phone_number02]').val('');
                    $('input[name=delivery_zipcode]').val('');
                    $('select[name=delivery_prefecture]').val('');
                    $('input[name=delivery_address1]').val('');
                    $('input[name=delivery_address2]').val('');
                    $('input[name=delivery_address3]').val('');

                    @if($isPeriodic == true)
                        $('#card_payment_button').prop('disabled', $('select[name=payment_method_id]').val() != 5 )
                    @endif

                    shop_memo_vue_customer.customer_id = new_customer.id;
                    shop_memo_vue_customer.shopMemos = new_customer.shop_memos;

                    localStorage.removeItem("new_customer");
                }
            };
            return false;
        }
    </script>
@endpush