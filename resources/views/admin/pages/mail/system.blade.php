@extends('admin.layouts.main.contents')

@section('title') {{__("admin.page_title.system_setting")}} @endsection

@section('contents')
    <form method="POST" accept-charset="UTF-8">
        <div class="row">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$system_setting->id}}">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        {{__("admin.page_header_name.system_setting_common")}}
                        <div class="card-header-actions">
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            {{-- システムのメールアドレス --}}
                            <div class="col-12 col-lg-6">
                                <div class="row form-group">
                                    <label class="col-4 col-form-label col-form-label-sm">{{__('admin.item_name.mail.system_mail')}}</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control form-control-sm @isInvalid($errors,'system_sender_mail_address')" name="system_sender_mail_address" pattern="^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$" title="{{__('validation.hint_text.email')}}" value="@if(old('system_sender_mail_address')){{old('system_sender_mail_address')}}@else{{$system_setting->system_sender_mail_address}}@endif" onchange="return app.functions.trim(this);" required>
                                        <div class="invalid-feedback">{{$errors->first('system_sender_mail_address')}}</div>
                                        <small class="form-text text-muted">{{__("admin.help_text.mail.system_mail")}}</small>
                                    </div>
                                </div>
                            </div>

                            {{-- システムメール署名 --}}
                            <div class="col-12 col-lg-6">
                                <div class="row form-group">
                                    <label class="col-4 col-form-label col-form-label-sm">{{__('admin.item_name.mail.system_signature')}}</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control form-control-sm @isInvalid($errors,'system_sender_signature')" name="system_sender_signature" value="@if(old('system_sender_signature')){{old('system_sender_signature')}}@else{{$system_setting->system_sender_signature}}@endif" onchange="return app.functions.trim(this);" required>
                                        <div class="invalid-feedback">{{$errors->first('system_sender_signature')}}</div>
                                        <small class="form-text text-muted">{{__("admin.help_text.mail.system_signature")}}</small>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        {{__("admin.page_header_name.system_setting_admin")}}
                        <div class="card-header-actions">
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            {{-- システム管理者 --}}
                            <div class="col-12 col-lg-6">
                                <div class="row form-group">
                                    <label class="col-4 col-form-label col-form-label-sm">{{__('admin.item_name.mail.system_admin')}}</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control form-control-sm @isInvalid($errors,'system_admin_mail_address')" name="system_admin_mail_address" pattern="^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$" title="{{__('validation.hint_text.email')}}" value="@if(old('system_admin_mail_address')){{old('system_admin_mail_address')}}@else{{$system_setting->system_admin_mail_address}}@endif" onchange="return app.functions.trim(this);" required>
                                        <div class="invalid-feedback">{{$errors->first('system_admin_mail_address')}}</div>
                                        <small class="form-text text-muted">{{__("admin.help_text.mail.system_admin")}}</small>
                                    </div>
                                </div>
                            </div>
                            {{-- 運用管理者 --}}
                            <div class="col-12 col-lg-6">
                                <div class="row form-group">
                                    <label class="col-4 col-form-label col-form-label-sm">{{__('admin.item_name.mail.operation_admin')}}</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control form-control-sm @isInvalid($errors,'operation_admin_mail_address')" name="operation_admin_mail_address" pattern="^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$" title="{{__('validation.hint_text.email')}}" value="@if(old('operation_admin_mail_address')){{old('operation_admin_mail_address')}}@else{{$system_setting->operation_admin_mail_address}}@endif" onchange="return app.functions.trim(this);" required>
                                        <div class="invalid-feedback">{{$errors->first('operation_admin_mail_address')}}</div>
                                        <small class="form-text text-muted">{{__("admin.help_text.mail.operation_admin")}}</small>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            @if($admin->admin_role_id==1 or $admin->admin_role_id==2)
                {{-- ボタン表示 start --}}
                <div class="col-12" class="mb-3">
                    <div class="row">
                        <div class="col-6 offset-3">
                            <button class="btn btn-sm btn-block btn-primary" type="submit">
                                <i class="{{__('admin.icon_class.save')}}"></i>&nbsp;{{__('admin.operation.save')}}
                            </button>
                        </div>
                    </div>
                </div>
                {{-- ボタン表示 end --}}
            @endif
        </div>
    </form>

@endsection

