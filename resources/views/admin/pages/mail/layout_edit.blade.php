@extends('admin.layouts.main.contents')

@section('title') {{__('admin.page_title.mail_layout_edit')}} @endsection

@section('contents')
    <form method="POST" accept-charset="UTF-8">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        {{__('admin.page_header_name.mail_layout_edit')}}
                        <div class="card-header-actions">
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            {{-- レイアウト名 --}}
                            <div class="col-12 col-lg-6">
                                <div class="row form-group">
                                    <label class="col-2 col-lg-4 col-form-label col-form-label-sm" for="text-input">{{__('admin.item_name.mail_layout.layout_name')}}</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control form-control-sm @isInvalid($errors,'mail_layout_name')" name="mail_layout_name" value="@if(old('mail_layout_name')){{old('mail_layout_name')}}@elseif(isset($layout)){{$layout->name}}@endif" placeholder="" required>
                                        <div class="invalid-feedback">{{$errors->first('mail_layout_name')}}</div>
                                        <small class="form-text text-muted">管理者が識別するための名称を入力</small>
                                    </div>
                                </div>
                            </div>
                            {{-- 備考 --}}
                            <div class="col-12 col-lg-6">
                                <div class="row form-group">
                                    <label class="col-2 col-lg-4 col-form-label col-form-label-sm" for="text-input">{{__('admin.item_name.mail_layout.remark')}}</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control form-control-sm @isInvalid($errors,'mail_layout_remark')" name="mail_layout_remark" value="@if(old('mail_layout_remark')){{old('mail_layout_remark')}}@elseif(isset($layout)){{$layout->remark}}@endif" placeholder="" required>
                                        <div class="invalid-feedback">{{$errors->first('mail_layout_remark')}}</div>
                                        <small class="form-text text-muted">管理者用の備考欄</small>
                                    </div>
                                </div>
                            </div>


                            {{-- 設置内容 --}}
                            <div class="col-12 col-lg-12">
                                <div class="row form-group">
                                    <label class="col-2 col-form-label col-form-label-sm">{{__('admin.item_name.mail_layout.header')}}</label>
                                    <div class="col-10">
                                        <textarea class="form-control form-control-sm @isInvalid($errors,'mail_layout_header')" name="mail_layout_header" rows="9" placeholder="Content.." required>@if(old('mail_layout_header')){{old('mail_layout_header')}}@elseif(isset($layout)){{get_contents($layout->header_file_path)}}@endif</textarea>
                                        <div class="invalid-feedback">{{$errors->first('mail_layout_header')}}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="offset-2 col-10">
                                <p class="text-muted text-center">・・・メール本文・・・</p>
                            </div>

                            {{-- 設置内容 --}}
                            <div class="col-12 col-lg-12">
                                <div class="row form-group">
                                    <label class="col-2 col-form-label col-form-label-sm">{{__('admin.item_name.mail_layout.footer')}}</label>
                                    <div class="col-10">
                                        <textarea class="form-control form-control-sm @isInvalid($errors,'mail_layout_footer')" name="mail_layout_footer" rows="9" placeholder="Content.." required>@if(old('mail_layout_footer')){{old('mail_layout_footer')}}@elseif(isset($layout)){{get_contents($layout->footer_file_path)}}@endif</textarea>
                                        <div class="invalid-feedback">{{$errors->first('mail_layout_footer')}}</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- ボタン表示 start --}}
            <div class="col-12" class="mb-20">
                <div class="row">
                    <div class="col-6">
                        <a href="{{route("admin.mail.layout")}}"><button class="btn btn-sm btn-block btn-secondary" type="button">{{__('admin.operation.back')}}</button></a>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-sm btn-block btn-primary" type="submit">{{__('admin.operation.save')}}</button>
                    </div>
                </div>
            </div>
            {{-- ボタン表示 end --}}

        </div>
    </form>

@endsection

