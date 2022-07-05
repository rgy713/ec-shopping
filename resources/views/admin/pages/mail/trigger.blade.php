@extends('admin.layouts.main.contents')

@inject('mailTriggerOrderTypeList', 'App\Common\KeyValueLists\MailTriggerOrderTypeList')
@inject('itemLineupList', 'App\Common\KeyValueLists\ItemLineupList')

@section('title') {{__('admin.page_title.auto_mail_setting')}} @endsection

@section('contents')
    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        {{__('admin.page_header_name.auto_mail_setting')}}
                        <div class="card-header-actions">
                        </div>
                    </div>

                    <div class="card-body">


                        <div class="row">
                            <div class="col-12">
                                <div class="row form-group">
                                    <label class="col-2 col-form-label form-control-sm">{{__('admin.item_name.auto_mail_setting.trigger_enabled')}}</label>
                                    <div class="col-10 form-control-sm">
                                        <div class="form-check form-check-inline mr-1">
                                            <input id="" class="form-check-input" name="mail_setting_enabled" value="TRUE"
                                                   type="radio" @if(old('mail_setting_enabled') && old('mail_setting_enabled')==TRUE)checked @elseif($trigger && $trigger->enabled)checked
                                                   @endif required>
                                            <label class="form-check-label" for="">{{__('admin.item_name.auto_mail_setting.enabled_true')}}</label>
                                        </div>

                                        <div class="form-check form-check-inline mr-1">
                                            <input id="" class="form-check-input" name="mail_setting_enabled" value="FALSE"
                                                   type="radio" @if(old('mail_setting_enabled') && old('mail_setting_enabled')==FALSE)checked @elseif($trigger && !$trigger->enabled)checked
                                                   @endif required>
                                            <label class="form-check-label" for="">{{__('admin.item_name.auto_mail_setting.enabled_false')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="row form-group">
                                    <label class="col-2 col-form-label form-control-sm">{{__('admin.item_name.auto_mail_setting.order_method')}}</label>
                                    <div class="col-10 form-control-sm">
                                        @foreach($mailTriggerOrderTypeList as $id => $name)
                                            <div class="form-check form-check-inline mr-1">
                                                <input id="order_method_{{$id}}" class="form-check-input"
                                                       name="mail_setting_order_method" type="radio" value="{{$id}}"
                                                       @if(old('mail_setting_order_method') && $id==old('mail_setting_order_method'))checked @elseif($trigger && $id==$trigger->order_method)checked @endif required>
                                                <label class="form-check-label"
                                                       for="order_method_{{$id}}">{{$name}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="row form-group">
                                    <label class="col-2 col-form-label form-control-sm">{{__('admin.item_name.auto_mail_setting.item_linup_id')}}</label>
                                    <div class="col-10 form-control-sm">
                                        @foreach($itemLineupList as $id => $name)
                                            <div class="form-check form-check-inline mr-1">
                                                <input id="item_linup_id_{{$id}}" class="form-check-input"
                                                       name="item_linup_id[]" type="checkbox" value="{{$id}}"
                                                       @if($lineups)
                                                           @foreach($lineups as $lineup)
                                                                @if($id==$lineup->item_lineup_id)checked @endif
                                                           @endforeach
                                                       @endif
                                                >
                                                <label class="form-check-label" for="item_linup_id_{{$id}}">{{$name}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="row form-group">
                                    <label class="col-2 col-form-label form-control-sm">{{__('admin.item_name.auto_mail_setting.elapsed_days')}}</label>
                                    <div class="col-6">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text form-control-sm">{{__('admin.item_name.shipping.estimated_arrival_date')}}から</span>
                                            <input class="form-control form-control-sm" name="elapsed_days"
                                                   type="number" min="1" max="32767" step="1" placeholder=""
                                                   value="@if(old('elapsed_days')){{old('elapsed_days')}}@elseif($trigger){{$trigger->elapsed_days}}@endif" required>
                                            <span class="input-group-text form-control-sm">{{__('admin.item_name.auto_mail_setting.days')}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="row form-group">
                                    <label class="col-2 col-form-label form-control-sm">{{__('admin.item_name.auto_mail_setting.first_purchase_only_flag')}}</label>
                                    <div class="col-10 form-control-sm">
                                        <div class="form-check form-check-inline mr-1">
                                            <input id="first_purchase_only_flag_0" class="form-check-input"
                                                   name="first_purchase_only_flag" type="radio" value="TRUE"
                                                   @if(old('first_purchase_only_flag') && old('first_purchase_only_flag')==TRUE)checked @elseif($trigger && $trigger->first_purchase_only_flag)checked
                                                   @endif required>
                                            <label class="form-check-label"
                                                   for="first_purchase_only_flag_0">{{__('admin.item_name.auto_mail_setting.first_purchase_only_flag_true')}}</label>
                                        </div>

                                        <div class="form-check form-check-inline mr-1">
                                            <input id="first_purchase_only_flag_1" class="form-check-input"
                                                   name="first_purchase_only_flag" type="radio" value="FALSE"
                                                   @if(old('first_purchase_only_flag') && old('first_purchase_only_flag')==FALSE)checked @elseif($trigger && !$trigger->first_purchase_only_flag)checked
                                                   @endif required>
                                            <label class="form-check-label"
                                                   for="first_purchase_only_flag_1">{{__('admin.item_name.auto_mail_setting.first_purchase_only_flag_false')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="row form-group">
                                    <label class="col-2 col-form-label form-control-sm">{{__('admin.item_name.auto_mail_setting.regular_member_only_flag')}}</label>
                                    <div class="col-10 form-control-sm">
                                        <div class="form-check form-check-inline mr-1">
                                            <input id="regular_member_only_flag_0" class="form-check-input"
                                                   name="regular_member_only_flag" type="radio" value="TRUE"
                                                   @if(old('regular_member_only_flag') && old('regular_member_only_flag')==TRUE)checked @elseif($trigger && $trigger->regular_member_only_flag)checked
                                                   @endif required>
                                            <label class="form-check-label"
                                                   for="regular_member_only_flag_0">{{__('admin.item_name.auto_mail_setting.regular_member_only_flag_true')}}</label>
                                        </div>

                                        <div class="form-check form-check-inline mr-1">
                                            <input id="regular_member_only_flag_1" class="form-check-input"
                                                   name="regular_member_only_flag" type="radio" value="FALSE"
                                                   @if(old('regular_member_only_flag') && old('regular_member_only_flag')==FALSE)checked  @elseif($trigger && !$trigger->regular_member_only_flag)checked
                                                   @endif required>
                                            <label class="form-check-label"
                                                   for="regular_member_only_flag_1">{{__('admin.item_name.auto_mail_setting.regular_member_only_flag_false')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="row form-group">
                                    <label class="col-2 col-form-label form-control-sm">{{__('admin.item_name.auto_mail_setting.customer_mail_magazine_flag')}}</label>
                                    <div class="col-10 form-control-sm">
                                        <div class="form-check form-check-inline mr-1">
                                            <input id="customer_mail_magazine_flag_0" class="form-check-input"
                                                   name="customer_mail_magazine_flag" type="radio" value="TRUE"
                                                   @if(old('customer_mail_magazine_flag') && old('customer_mail_magazine_flag')==TRUE)checked @elseif($trigger && $trigger->customer_mail_magazine_flag)checked
                                                   @endif required>
                                            <label class="form-check-label" for="customer_mail_magazine_flag_0">{{__('admin.item_name.auto_mail_setting.customer_mail_magazine_flag_true')}}</label>
                                        </div>
                                        <div class="form-check form-check-inline mr-1">
                                            <input id="customer_mail_magazine_flag_1" class="form-check-input"
                                                   name="customer_mail_magazine_flag" type="radio" value="FALSE"
                                                   @if(old('customer_mail_magazine_flag') && old('customer_mail_magazine_flag')==FALSE)checked @elseif($trigger && !$trigger->customer_mail_magazine_flag)checked
                                                   @endif required>
                                            <label class="form-check-label"
                                                   for="customer_mail_magazine_flag_1">{{__('admin.item_name.auto_mail_setting.customer_mail_magazine_flag_false')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- ボタン --}}
        <div class="row">
            <div class="col-12">
                <div class="row mb-3">
                    <div class="col-6">
                        <a href="{{route("admin.mail.template")}}">
                            <button class="btn btn-sm btn-block btn-secondary" type="button">
                                <i class="{{__('admin.icon_class.back')}}"></i>&nbsp;{{__('admin.operation.back')}}
                            </button>
                        </a>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-sm btn-block btn-primary" type="submit">
                            <i class="{{__('admin.icon_class.save')}}"></i>&nbsp;{{__('admin.operation.save')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- ボタン --}}
    </form>

@endsection

