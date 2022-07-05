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

{{-- 名前 --}}
<div class="col-12">
    <div class="row form-group">
        <label class="{{$colLabelFor12}} {{$colLgLabelFor12}} col-form-label form-control-sm" >{{__("common.item_name.address.name")}}</label>

        <div class="{{$colForm2For12}} {{$colLgForm2For12}}">
            <input name="{{$prefix}}_name01" type="text" readonly class="form-control-plaintext form-control-sm"
                   value="@if(isset($order) && isset($order[$prefix.'_name01'])){{$order[$prefix.'_name01']}}@endif">
        </div>

        <div class="{{$colForm2For12}} {{$colLgForm2For12}}">
            <input name="{{$prefix}}_name02" type="text" readonly class="form-control-plaintext form-control-sm"
                   value="@if(isset($order) && isset($order[$prefix.'_name02'])){{$order[$prefix.'_name02']}}@endif">
        </div>
    </div>
</div>

{{-- フリガナ --}}
<div class="col-12">
    <div class="row form-group">
        <label class="{{$colLabelFor12}} {{$colLgLabelFor12}} col-form-label form-control-sm" >{{__("common.item_name.address.kana")}}</label>
        <div class="{{$colForm2For12}} {{$colLgForm2For12}}">
            <input name="{{$prefix}}_kana01" type="text" readonly class="form-control-plaintext form-control-sm"
                   value="@if(isset($order) && isset($order[$prefix.'_kana01'])){{$order[$prefix.'_kana01']}}@endif">
        </div>

        <div class="{{$colForm2For12}} {{$colLgForm2For12}}">
            <input name="{{$prefix}}_kana02" type="text" readonly class="form-control-plaintext form-control-sm"
                   value="@if(isset($order) && isset($order[$prefix.'_kana02'])){{$order[$prefix.'_kana02']}}@endif">
        </div>
    </div>
</div>

{{-- 電話番号 --}}
<div class="col-12">
    <div class="row form-group">
        <label class="{{$colLabelFor12}} {{$colLgLabelFor12}} col-form-label form-control-sm" >{{__("common.item_name.address.tel")}}</label>
        <div class="{{$colForm2For12}} {{$colLgForm2For12}}">
            <input name="{{$prefix}}_phone_number01" type="text" readonly class="form-control-plaintext form-control-sm"
                   value="@if(isset($order) && isset($order[$prefix.'_phone_number01'])){{$order[$prefix.'_phone_number01']}}@endif">
        </div>

        <div class="{{$colForm2For12}} {{$colLgForm2For12}}">
            <input name="{{$prefix}}_phone_number02" type="text" readonly class="form-control-plaintext form-control-sm"
                   value="@if(isset($order) && isset($order[$prefix.'_phone_number02'])){{$order[$prefix.'_phone_number02']}}@endif">
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
                <input name="{{$prefix}}_zipcode" type="text" readonly class="form-control-plaintext form-control-sm"
                       value="@if(isset($order) && isset($order[$prefix.'_zipcode'])){{$order[$prefix.'_zipcode']}}@endif">
            </div>
        </div>
    </div>
</div>

{{-- 都道府県 --}}
<div class="col-6">
    <div class="row form-group">
        <label class="{{$colLabelFor6}} col-form-label form-control-sm" >{{__("common.item_name.address.prefecture")}}</label>
        <div class="{{$colFormFor6}}">
            <input type="hidden" name="{{$prefix}}_prefecture" value="@if(isset($order) && isset($order[$prefix.'_prefecture'])){{$order[$prefix.'_prefecture']}}@endif">
            <input type="text" readonly class="form-control-plaintext form-control-sm"
                   value="@if(isset($order) && isset($order[$prefix.'_prefecture'])){{$prefectureList[$order[$prefix.'_prefecture']]}}@endif">
        </div>
    </div>
</div>

{{-- 住所1 --}}
<div class="col-12">
    <div class="row form-group">
        <label class="{{$colLabelFor12}} col-form-label form-control-sm" >{{__("common.item_name.address.address1")}}</label>
        <div class="{{$colForm1For12}}">
            <input name="{{$prefix}}_address1" type="text" readonly class="form-control-plaintext form-control-sm"
                   value="@if(isset($order) && isset($order[$prefix.'_address1'])){{$order[$prefix.'_address1']}}@endif">
        </div>
    </div>
</div>
{{-- 住所2 --}}
<div class="col-12">
    <div class="row form-group">
        <label class="{{$colLabelFor12}} col-form-label form-control-sm">{{__("common.item_name.address.address2")}}</label>
        <div class="{{$colForm1For12}}">
            <input name="{{$prefix}}_address2" type="text" readonly class="form-control-plaintext form-control-sm"
                   value="@if(isset($order) && isset($order[$prefix.'_address2'])){{$order[$prefix.'_address2']}}@endif">
        </div>
    </div>
</div>
{{-- 住所3 --}}
<div class="col-12">
    <div class="row form-group">
        <label class="{{$colLabelFor12}} col-form-label form-control-sm" >{{__("common.item_name.address.address3")}}</label>
        <div class="{{$colForm1For12}}">
            <input name="{{$prefix}}_address3" type="text" readonly class="form-control-plaintext form-control-sm"
                   value="@if(isset($order) && isset($order[$prefix.'_address3'])){{$order[$prefix.'_address3']}}@endif">
        </div>
    </div>
</div>
