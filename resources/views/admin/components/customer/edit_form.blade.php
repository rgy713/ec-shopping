@inject('customerStatusList', 'App\Common\KeyValueLists\CustomerStatusList')
@inject('mailMagazineFlagList', 'App\Common\KeyValueLists\MailMagazineFlagList')
@inject('directMailFlagList', 'App\Common\KeyValueLists\DirectMailFlagList')
@inject('mediaCodeList', 'App\Common\KeyValueLists\MediaCodeList')
@inject('commonEmailDomainList', 'App\Common\KeyValueLists\CommonEmailDomainList')
@inject('wholesaleFlagList', 'App\Common\KeyValueLists\WholesaleFlagList')

<div class="card" id="customer_edit_form">
    <div class="card-header">
        <i class="fa fa-user"></i>&nbsp;{{__('admin.page_header_name.customer_information')}}

        <div class="card-header-actions">
            @if($mode==="edit")
            <a class="card-header-action btn-close" onClick="PopupWindow('{{route("admin.order.popup.create",['customer_id'=>$customer->id])}}')">
                <button class="btn btn-sm btn-primary" type="button"><i class="icon icon-basket"></i>&nbsp;{{__("admin.item_name.common.order_create")}}</button>
            </a>
            <a class="card-header-action btn-setting" onclick="PopupWindow('{{route("admin.periodic.popup.create",['customer_id'=>$customer->id])}}')">
                <button class="btn btn-sm btn-primary" type="button"><i class="icon icon-refresh"></i>&nbsp;{{__("admin.item_name.common.periodic_create")}}</button>
            </a>
            @endif
        </div>

    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    {{-- 顧客ID --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-2 col-lg-2 col-form-label form-control-sm">{{__("admin.item_name.customer.id")}}</label>
                            <div class="col-10 col-lg-10">
                                <input readonly class="form-control form-control-sm" name="customer_id" type="text" placeholder="" value="@if(isset($customer)){{$customer->id}}@endif">
                            </div>
                        </div>
                    </div>

                    {{-- 会員状態 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-2 col-lg-2 col-form-label form-control-sm">{{__("admin.item_name.customer.status")}}</label>
                            <div class="col-10 col-lg-10 col-form-label form-control-sm">
                                @foreach($customerStatusList as $id => $name)
                                    <div class="form-check form-check-inline mr-1">
                                        <input id="customer-edit-form-status-{{$id}}" class="form-check-input" name="customer_status" v-model="customer_status" type="radio" value="{{$id}}" @if(old('customer_status'))@if($id==old('customer_status'))checked @endif @elseif(isset($customer) && $id==$customer->customer_status_id)checked @elseif(!old('customer_status') && !isset($customer) && $id==3) checked @endif required>
                                        <label class="form-check-label" for="customer-edit-form-status-{{$id}}">{{$name}}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    {{-- 卸 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-2 col-lg-2 col-form-label form-control-sm">{{__("admin.item_name.customer.wholesale_flag")}}</label>
                            <div class="col-10 col-lg-10 col-form-label form-control-sm">
                                @foreach($wholesaleFlagList as $id => $name)
                                    <div class="form-check form-check-inline mr-1">
                                        <input id="customer-edit-form-wholesale-flag-{{$id}}" class="form-check-input" name="customer_wholesale_flag" type="radio" value="{{$id}}" @if(old('customer_wholesale_flag'))@if($id==old('customer_wholesale_flag'))checked @endif @elseif(isset($customer) && $id==($customer->wholesale_flag?0:1))checked @elseif(!old('customer_wholesale_flag') && !isset($customer) && $id==1) checked @endif required>
                                        <label class="form-check-label" for="customer-edit-form-wholesale-flag-{{$id}}">{{$name}}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- メールアドレス --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-2 col-lg-2 col-form-label form-control-sm">{{__("admin.item_name.customer.email")}}</label>

                            <div class="col-10 col-lg-10">
                                <div class="row">
                                    <div class="col-5 pr-0">
                                        <input class="form-control form-control-sm @isInvalid($errors,'customer_email_name') @isInvalid($errors,'customer_email')" name="customer_email_name" type="text" placeholder="user.name" onchange="return app.functions.trim(this);" pattern="^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+$" title="{{__('validation.hint_text.customer_email_domain')}}" value="@if(null!==old('customer_email_name')){{old('customer_email_name')}}@elseif(isset($customer)){{explode_email($customer->email,0)}}@endif" v-bind:required="customer_status == 2" onchange="return app.functions.trim(this);">
                                        <div class="invalid-feedback">{{$errors->first('customer_email_name')}}</div>
                                        @if(!$errors->first('customer_email_name'))
                                            <div class="invalid-feedback">{{$errors->first('customer_email')}}</div>
                                        @endif
                                    </div>
                                    <div class="col-7 pl-0">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text form-control-sm">@</span>
                                            <input class="form-control form-control-sm @isInvalid($errors,'customer_email_domain')" name="customer_email_domain" type="text" placeholder="example.com" list="enabled-domains" onchange="return app.functions.trim(this);" pattern="^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$" title="{{__('validation.hint_text.customer_email_domain')}}" value="@if(null!==old('customer_email_domain')){{old('customer_email_domain')}}@elseif(isset($customer)){{explode_email($customer->email,1)}}@endif" v-bind:required="customer_status == 2" onchange="return app.functions.trim(this);">
                                            <datalist id="enabled-domains">
                                                @foreach($commonEmailDomainList as $item)
                                                    <option value="{{$item}}">{{$item}}</option>
                                                @endforeach
                                            </datalist>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- パスワード --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-2 col-lg-2 col-form-label form-control-sm">{{__("admin.item_name.customer.password")}}</label>
                            <div class="col-10 col-lg-10">
                                <input class="form-control form-control-sm @isInvalid($errors,'customer_password')" name="customer_password" type="text" placeholder="" onchange="return app.functions.trim(this);" pattern="^(?=.*?[a-zA-Z])(?=.*?\d)[a-zA-Z0-9!#$%&()*+,.:;=?@\[\]^_{}-]+$" title="{{__('validation.hint_text.alpha_num_symbol_both')}}" value="@if(old('customer_password')){{old('customer_password')}}@elseif(isset($customer)){{$customer->password}}@endif" v-bind:required="customer_status == 2">
                                <div class="invalid-feedback">{{$errors->first('customer_password')}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="col-12">
                <div class="row">
                    @include('admin.components.common.address_form',['prefix'=>'customer', 'obj'=>null])
                </div>
            </div>


            <div class="col-12">
                <hr>
            </div>

            <div class="col-12">
                <div class="row">
                    {{-- 広告No. --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-2 col-lg-2 col-form-label form-control-sm">{{__("admin.item_name.media.code")}}</label>
                            <div class="col-10 col-lg-10">
                                @php
                                    $readonly = FALSE;
                                    if($mode==="edit")
                                    {
                                        if($admin->admin_role_id==4 || $admin->admin_role_id==6 )
                                        {
                                            $readonly = TRUE;
                                        }
                                        elseif($admin->admin_role_id==3 || $admin->admin_role_id==5 || $admin->admin_role_id==7)
                                        {
                                            if(isset($customer) && ($customer->advertising_media_code<9000 || $customer->advertising_media_code>9999))
                                                $readonly = TRUE;
                                        }
                                    }
                                @endphp
                                <input class="form-control form-control-sm @isInvalid($errors,'customer_advertising_media_code')" name="customer_advertising_media_code" @if($mode==="edit" && $readonly)readonly @endif type="text" placeholder="" pattern="^[0-9]+$" maxlength="7" list="media-code-list" value="@if(old('customer_advertising_media_code')){{old('customer_advertising_media_code')}}@elseif(isset($customer)){{$customer->advertising_media_code}}@endif">
                                <div class="invalid-feedback">{{$errors->first('customer_advertising_media_code')}}</div>
                                <datalist id="media-code-list">
                                    @foreach($mediaCodeList as $key => $item)
                                        <option value="{{$key}}">{{$item}}</option>
                                    @endforeach
                                </datalist>

                            </div>
                        </div>
                    </div>

                    {{-- 生年月日 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-2 col-lg-2 col-form-label form-control-sm">{{__("admin.item_name.customer.birthday")}}</label>
                            <div class="col-10 col-lg-10">
                                <input class="form-control form-control-sm @isInvalid($errors,'customer_birthday')" name="customer_birthday" type="date" placeholder="" value="@if(old('customer_birthday')){{old('customer_birthday')}}@elseif(isset($customer)){{$customer->birthday}}@endif" required>
                                <div class="invalid-feedback">{{$errors->first('customer_birthday')}}</div>
                            </div>
                        </div>
                    </div>

                    {{-- 架電禁止 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-2 col-lg-2 col-form-label form-control-sm">{{__("admin.item_name.customer.no_phone_call")}}</label>
                            <div class="col-10 col-lg-10 col-form-label form-control-sm @isInvalid($errors,'customer_no_phone_call')">
                                <div class="form-check form-check-inline mr-1">
                                    <input class="form-check-input" id="no-phone-call" name="customer_no_phone_call" type="checkbox" value="1" @if(old('customer_no_phone_call'))checked @elseif(isset($customer) && $customer->no_phone_call_flag)checked @endif>
                                    <label class="form-check-label" for="no-phone-call"></label>
                                </div>
                            </div>
                            <div class="invalid-feedback">{{$errors->first('customer_no_phone_call')}}</div>
                        </div>
                    </div>

                    {{-- メルマガ --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-2 col-lg-2 col-form-label form-control-sm">{{__("admin.item_name.customer.mail_magazine_flag")}}</label>
                            <div class="col-10 col-lg-10 col-form-label form-control-sm @isInvalid($errors,'customer_mail_flag')">
                                @foreach($mailMagazineFlagList as $id => $name)
                                    <div class="form-check form-check-inline mr-1">
                                        <input id="customer-edit-form-mail-flag-{{$id}}" class="form-check-input" name="customer_mail_flag" type="radio" value="{{$id}}" @if(old('customer_mail_flag'))@if($id==old('customer_mail_flag'))checked @endif @elseif(isset($customer) && $id==($customer->mail_magazine_flag?2:3))checked @elseif(!old('customer_no_phone_call') && !isset($customer) && $id==2) checked @endif required>
                                        <label class="form-check-label" for="customer-edit-form-mail-flag-{{$id}}">{{$name}}</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="invalid-feedback">{{$errors->first('customer_mail_flag')}}</div>
                        </div>
                    </div>

                    {{-- DM可否 --}}
                    <div class="col-12 col-lg-12">
                        <div class="row form-group">
                            <label class="col-2 col-lg-2 col-form-label form-control-sm">{{__("admin.item_name.customer.dm_flag")}}</label>
                            <div class="col-10 col-lg-10 col-form-label form-control-sm @isInvalid($errors,'customer_direct_mail_flag')">
                                @foreach($directMailFlagList as $id => $name)
                                    <div class="form-check form-check-inline mr-1">
                                        <input id="customer-edit-form-dm-flag-{{$id}}" class="form-check-input" type="radio" value="{{$id}}" name="customer_direct_mail_flag"  @if(old('customer_direct_mail_flag'))@if($id==old('customer_direct_mail_flag'))checked @endif @elseif(isset($customer) && $id==($customer->dm_flag?1:2))checked @elseif(!old('customer_direct_mail_flag') && !isset($customer) && $id==1) checked @endif required>
                                        <label class="form-check-label" for="customer-edit-form-dm-flag-{{$id}}">{{$name}}</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="invalid-feedback">{{$errors->first('customer_direct_mail_flag')}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('content_js')
    <script>
        new Vue({
            el: '#customer_edit_form',
            data: {
                customer_status: $("input[name=customer_status]:checked").val()
            }
        });
    </script>
@endpush()