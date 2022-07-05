@inject('prefectureList', 'App\Common\KeyValueLists\PrefectureList')

{{-- 親要素は col-12 col-lg-6 を想定 --}}
@php

    $colLabelFor12="col-2";
    $colForm1For12="col-10";
    $colForm2For12="col-5";

    $colLgLabelFor12="col-lg-2";
    $colLgForm1For12="col-lg-10";
    $colLgForm2For12="col-lg-5";

    $colLabelFor6="col-4";
    $colFormFor6="col-8";

@endphp

@push('content_js')
    {{-- 入力補助をあてる --}}
    <script>
        $(function () {
            //ボタンクリック時
            $('#{{$prefix}}-zipcode-button').on('click',function(){
                return app.functions.setAddress("#{{$prefix}}-zipcode-input","#{{$prefix}}-pref-input","#{{$prefix}}-address1-input","#{{$prefix}}-address2-input");
            });
            //フォーカス外れた時
            $('#{{$prefix}}-zipcode-input').on('blur',function(){
                return app.functions.setAddress("#{{$prefix}}-zipcode-input","#{{$prefix}}-pref-input","#{{$prefix}}-address1-input","#{{$prefix}}-address2-input");
            });

            autokana.bind("#{{$prefix}}-name01-input","#{{$prefix}}-kana01-input",{ katakana: true });
            autokana.bind("#{{$prefix}}-name02-input","#{{$prefix}}-kana02-input",{ katakana: true });
        });
    </script>
@endpush

{{-- 名前 --}}
<div class="col-12">
    <div class="row form-group">
        <label class="{{$colLabelFor12}} {{$colLgLabelFor12}} col-form-label form-control-sm" >{{__("common.item_name.address.name")}}</label>

        <div class="{{$colForm2For12}} {{$colLgForm2For12}}">
            <input id="{{$prefix}}-name01-input" class="form-control form-control-sm @isInvalid($errors, $prefix.'_name01')" name="{{$prefix}}_name01" type="text" placeholder="{{__("common.placeholder.address.name01")}}"
                   value="@if(!is_null(old($prefix.'_name01'))){{old($prefix.'_name01')}}@elseif(!is_null($obj) && isset($obj[$prefix.'_name01'])){{$obj[$prefix.'_name01']}}@elseif(isset($customer)){{$customer->name01}}@endif" required onchange="return app.functions.trim(this);">
            <div class="invalid-feedback">{{$errors->first($prefix.'_name01')}}</div>
        </div>

        <div class="{{$colForm2For12}} {{$colLgForm2For12}}">
            <input id="{{$prefix}}-name02-input" class="form-control form-control-sm @isInvalid($errors, $prefix.'_name02')" name="{{$prefix}}_name02" type="text" placeholder="{{__("common.placeholder.address.name02")}}"
                   value="@if(!is_null(old($prefix.'_name02'))){{old($prefix.'_name02')}}@elseif(!is_null($obj) && isset($obj[$prefix.'_name02'])){{$obj[$prefix.'_name02']}}@elseif(isset($customer)){{$customer->name02}}@endif" required onchange="return app.functions.trim(this);">
            <div class="invalid-feedback">{{$errors->first($prefix.'_name02')}}</div>
        </div>
    </div>
</div>

{{-- フリガナ --}}
<div class="col-12">
    <div class="row form-group">
        <label class="{{$colLabelFor12}} {{$colLgLabelFor12}} col-form-label form-control-sm" >{{__("common.item_name.address.kana")}}</label>
        <div class="{{$colForm2For12}} {{$colLgForm2For12}}">
            <input id="{{$prefix}}-kana01-input" class="form-control form-control-sm @isInvalid($errors, $prefix.'_kana01')" name="{{$prefix}}_kana01" type="text" pattern="[\u30A0-\u30FF]*" title="{{__('validation.hint_text.customer_kana')}}"
                   value="@if(!is_null(old($prefix.'_kana01'))){{old($prefix.'_kana01')}}@elseif(!is_null($obj) && isset($obj[$prefix.'_kana01'])){{$obj[$prefix.'_kana01']}}@elseif(isset($customer)){{$customer->kana01}}@endif" placeholder="{{__("common.placeholder.address.kana01")}}" required>
            <div class="invalid-feedback">{{$errors->first($prefix.'_kana01')}}</div>
        </div>

        <div class="{{$colForm2For12}} {{$colLgForm2For12}}">
            <input id="{{$prefix}}-kana02-input" class="form-control form-control-sm @isInvalid($errors, $prefix.'_kana02')" name="{{$prefix}}_kana02" type="text" pattern="[\u30A0-\u30FF]*" title="{{__('validation.hint_text.customer_kana')}}"
                   value="@if(!is_null(old($prefix.'_kana02'))){{old($prefix.'_kana02')}}@elseif(!is_null($obj) && isset($obj[$prefix.'_kana02'])){{$obj[$prefix.'_kana02']}}@elseif(isset($customer)){{$customer->kana02}}@endif" placeholder="{{__("common.placeholder.address.kana02")}}" required>
            <div class="invalid-feedback">{{$errors->first($prefix.'_kana02')}}</div>
        </div>
    </div>
</div>

{{-- 電話番号 --}}
<div class="col-12">
    <div class="row form-group">
        <label class="{{$colLabelFor12}} {{$colLgLabelFor12}} col-form-label form-control-sm" >{{__("common.item_name.address.tel")}}</label>
        <div class="{{$colForm2For12}} {{$colLgForm2For12}}">
            <input class="form-control form-control-sm @isInvalid($errors, $prefix.'_phone_number01')" name="{{$prefix}}_phone_number01" type="text" pattern="^[0-9]+$" maxlength="11" title="{{__('validation.hint_text.customer_phone_number')}}"
                   value="@if(!is_null(old($prefix.'_phone_number01'))){{old($prefix.'_phone_number01')}}@elseif(!is_null($obj) && isset($obj[$prefix.'_phone_number01'])){{$obj[$prefix.'_phone_number01']}}@elseif(isset($customer)){{$customer->phone_number01}}@endif" placeholder="0612345678" required>
            <div class="invalid-feedback">{{$errors->first($prefix.'_phone_number01')}}</div>
        </div>

        <div class="{{$colForm2For12}} {{$colLgForm2For12}}">
            <input class="form-control form-control-sm @isInvalid($errors, $prefix.'_phone_number02')" name="{{$prefix}}_phone_number02" type="text" pattern="^[0-9]+$" maxlength="11" title="{{__('validation.hint_text.customer_phone_number')}}"
                   value="@if(!is_null(old($prefix.'_phone_number02'))){{old($prefix.'_phone_number02')}}@elseif(!is_null($obj) && isset($obj[$prefix.'_phone_number02'])){{$obj[$prefix.'_phone_number02']}}@elseif(isset($customer)){{$customer->phone_number02}}@endif" placeholder="08012345678">
            <div class="invalid-feedback">{{$errors->first($prefix.'_phone_number02')}}</div>
        </div>
    </div>
</div>

{{-- 郵便番号 --}}
<div class="col-12">
    <div class="row form-group">
        <label class="{{$colLabelFor12}} col-form-label form-control-sm" >{{__("common.item_name.address.zip")}}</label>
        <div class="{{$colForm1For12}}">
            <div class="input-group-prepend">
                <span class="input-group-text form-control-sm">〒</span>
                <input id="{{$prefix}}-zipcode-input" class="form-control form-control-sm @isInvalid($errors, $prefix.'_zipcode')" name="{{$prefix}}_zipcode" type="text" pattern="^[0-9]+$" minlength="7" title="{{__('validation.hint_text.customer_zipcode')}}" maxlength="7"
                       value="@if(!is_null(old($prefix.'_zipcode'))){{old($prefix.'_zipcode')}}@elseif(!is_null($obj) && isset($obj[$prefix.'_zipcode'])){{$obj[$prefix.'_zipcode']}}@elseif(isset($customer)){{$customer->zipcode}}@endif" placeholder="1112222" required>
                <div class="invalid-feedback">{{$errors->first($prefix.'_zipcode')}}</div>
                <button id="{{$prefix}}-zipcode-button" class="btn-zip input-group-text form-control-sm btn btn-sm btn-secondary" type="button">
                    <i class="fa fa-refresh"></i>
                </button>
            </div>

        </div>
    </div>
</div>

{{-- 都道府県 --}}
<div class="col-6">
    <div class="row form-group">
        <label class="{{$colLabelFor6}} col-form-label form-control-sm" >{{__("common.item_name.address.prefecture")}}</label>
        <div class="{{$colFormFor6}}">
            <select id="{{$prefix}}-pref-input" class="form-control form-control-sm @isInvalid($errors, $prefix.'_prefecture')" name="{{$prefix}}_prefecture" required>
                <option value=""></option>
                @foreach($prefectureList as $id => $name)
                    <option value="{{$id}}" @if(!is_null(old($prefix.'_prefecture')) && $id==old($prefix.'_prefecture'))selected @elseif(!is_null($obj) && isset($obj[$prefix.'_prefecture']) && $id==$obj[$prefix.'_prefecture']) selected @elseif(isset($customer) && $id==$customer->prefecture_id) selected @endif>{{$name}}</option>
                @endforeach
            </select>
            <div class="invalid-feedback">{{$errors->first($prefix.'_prefecture')}}</div>
        </div>
    </div>
</div>

{{-- 住所1 --}}
<div class="col-12">
    <div class="row form-group">
        <label class="{{$colLabelFor12}} col-form-label form-control-sm" >{{__("common.item_name.address.address1")}}</label>
        <div class="{{$colForm1For12}}">
            <input id="{{$prefix}}-address1-input" class="form-control form-control-sm @isInvalid($errors, $prefix.'_address1')" name="{{$prefix}}_address1" type="text"
                   value="@if(!is_null(old($prefix.'_address1'))){{old($prefix.'_address1')}}@elseif(!is_null($obj) && isset($obj[$prefix.'_address1'])){{$obj[$prefix.'_address1']}}@elseif(isset($customer)){{$customer->address01}}@endif" placeholder="{{__("common.placeholder.address.address1")}}" required onchange="return app.functions.trim(this);">
            <div class="invalid-feedback">{{$errors->first($prefix.'_address1')}}</div>
        </div>
    </div>
</div>
{{-- 住所2 --}}
<div class="col-12">
    <div class="row form-group">
        <label class="{{$colLabelFor12}} col-form-label form-control-sm">{{__("common.item_name.address.address2")}}</label>
        <div class="{{$colForm1For12}}">
            <input id="{{$prefix}}-address2-input" class="form-control form-control-sm @isInvalid($errors, $prefix.'_address2')" name="{{$prefix}}_address2" type="text"
                   value="@if(!is_null(old($prefix.'_address2'))){{old($prefix.'_address2')}}@elseif(!is_null($obj) && isset($obj[$prefix.'_address2'])){{$obj[$prefix.'_address2']}}@elseif(isset($customer)){{$customer->address02}}@endif" placeholder="{{__("common.placeholder.address.address2")}}" required onchange="return app.functions.trim(this);">
            <div class="invalid-feedback">{{$errors->first($prefix.'_address2')}}</div>
        </div>
    </div>
</div>
{{-- 住所3 --}}
<div class="col-12">
    <div class="row form-group">
        <label class="{{$colLabelFor12}} col-form-label form-control-sm" >{{__("common.item_name.address.address3")}}</label>
        <div class="{{$colForm1For12}}">
            <input id="{{$prefix}}-address3-input" class="form-control form-control-sm @isInvalid($errors, $prefix.'_address3')" name="{{$prefix}}_address3" type="text"
                   value="@if(!is_null(old($prefix.'_address3'))){{old($prefix.'_address3')}}@elseif(!is_null($obj) && isset($obj[$prefix.'_address3'])){{$obj[$prefix.'_address3']}}@elseif(isset($customer)){{$customer->requests_for_delivery}}@endif" placeholder="{{__("common.placeholder.address.address3")}}" onchange="return app.functions.trim(this);">
            <div class="invalid-feedback">{{$errors->first($prefix.'_address3')}}</div>
        </div>
    </div>
</div>
