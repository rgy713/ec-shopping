@extends('admin.layouts.main.contents')

@inject('mailLayoutList', 'App\Common\KeyValueLists\MailLayoutList')
@inject('MailLayoutHeaderFilePathList', 'App\Common\KeyValueLists\MailLayoutHeaderFilePathList')
@inject('MailLayoutFooterFilePathList', 'App\Common\KeyValueLists\MailLayoutFooterFilePathList')
@inject('SystemSetting', 'App\Common\SystemSetting')

@section('title') {{__('admin.page_title.mail_template_edit')}} @endsection

@section('contents')
    <form id="mail_layout_edit_form" method="POST" accept-charset="UTF-8">
        <div class="row">
            {{ csrf_field() }}
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        {{__('admin.page_header_name.mail_template_detail')}}
                        <div class="card-header-actions">

                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            {{-- 送信契機 --}}
                            <input type="hidden" name="id" value="@if(isset($template)){{$template->id}}@endif">
                            <div class="col-12 col-lg-12">
                                <div class="row form-group">
                                    <label class="col-2 col-lg-2 col-form-label col-form-label-sm" for="text-input">{{__('admin.item_name.mail.trigger')}}</label>
                                    <div class="col-10">
                                        <input type="text" readonly class="form-control-plaintext form-control-sm" name="sending_trigger" value="@if(isset($template)){{$template->sending_trigger}}@else{{__('admin.item_name.mail.step_mail_progress_time')}}@endif">
                                    </div>
                                </div>
                            </div>

                            {{-- 名称 --}}
                            <div class="col-12 col-lg-6">
                                <div class="row form-group">
                                    <label class="col-2 col-lg-4 col-form-label col-form-label-sm" for="text-input">{{__('admin.item_name.mail.template_name')}}</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control form-control-sm @isInvalid($errors,'mail_template_name')" name="mail_template_name" value="@if(old('mail_template_name')){{ old('mail_template_name') }}@elseif(isset($template)){{$template->name}}@endif" placeholder="" required>
                                        <div class="invalid-feedback">{{$errors->first('mail_template_name')}}</div>
                                        <small class="form-text text-muted">管理者が識別するための名称を入力</small>
                                    </div>
                                </div>
                            </div>

                            {{-- レイアウト --}}
                            <div class="col-12 col-lg-6">
                                <div class="row form-group">
                                    <label class="col-2 col-lg-4 col-form-label col-form-label-sm">{{__('admin.item_name.mail.layout')}}</label>
                                    <div class="col-10 col-lg-8">
                                        <select class="form-control form-control-sm" name="mail_layout_id" onchange="change_mail_layout(this);" required>
                                            @foreach($mailLayoutList as $id => $name)
                                                <option value="{{$id}}" @if(old('mail_layout_id') && $id==old('mail_layout_id'))selected @elseif(isset($template) && $id == $template->mail_layout_id)selected @endif>{{$name}}</option>
                                            @endforeach
                                        </select>
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
                        {{__('admin.page_header_name.mail_template_content')}}
                        <div class="card-header-actions">
                            <button class="btn btn-sm btn-secondary"type="button" onclick="mail_preview('{{$SystemSetting->operationAdminMailAddress()}}', '{{route('admin.mail.template.preview')}}')">{{__('admin.item_name.mail.preview')}}</button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            {{-- 件名 --}}
                            <div class="col-12 col-lg-6">
                                <div class="row form-group">
                                    <label class="col-2 col-lg-4 col-form-label col-form-label-sm" for="text-input">{{__('admin.item_name.mail.subject')}}</label>
                                    <div class="col-8">
                                        <input type="text" class="form-control form-control-sm @isInvalid($errors,'mail_template_subject')" name="mail_template_subject" placeholder="" value="@if(old('mail_template_subject')){{ old('mail_template_subject') }}@elseif(isset($template)){{$template->subject}}@endif" required>
                                        <div class="invalid-feedback">{{$errors->first('mail_template_subject')}}</div>
                                        <small class="form-text text-muted">メール送信時の件名</small>
                                    </div>
                                </div>
                            </div>

                            <div id="mail_layout_header" class="offset-2 col-10">
                                @foreach($MailLayoutHeaderFilePathList as $id => $header_file_path)
                                    <p class="mail-layout-id-{{$id}} text-muted d-none">{!! get_contents_html($header_file_path) !!}</p>
                                @endforeach
                            </div>

                            {{-- メール本文 --}}
                            <div class="col-12 col-lg-12">
                                <div class="row form-group">
                                    <label class="col-2 col-form-label col-form-label-sm">{{__('admin.item_name.mail.body')}}</label>
                                    <div class="col-10">
                                        <textarea class="form-control form-control-sm @isInvalid($errors,'mail_template_body')" name="mail_template_body" rows="18" placeholder="Content.." required>@if(old('mail_template_body')){{ old('mail_template_body') }}@elseif(isset($template)){{get_contents($template->body_file_path)}}@endif</textarea>
                                        <div class="invalid-feedback">{{$errors->first('mail_template_body')}}</div>
                                    </div>
                                </div>
                            </div>

                            <div id="mail_layout_footer" class="offset-2 col-10">
                                @foreach($MailLayoutFooterFilePathList as $id => $footer_file_path)
                                    <p class="mail-layout-id-{{$id}} text-muted d-none">{!! get_contents_html($footer_file_path) !!}</p>
                                @endforeach
                            </div>

                            <div class="col-12">
                                <hr />
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            {{-- ボタン表示 start --}}
            <div class="col-12" class="mb-20">
                <div class="row">
                    <div class="col-6">
                        <a href="{{route("admin.mail.template")}}"><button class="btn btn-sm btn-block btn-secondary" type="button">{{__('admin.operation.back')}}</button></a>
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
@push('content_js')
    <script>
        function change_mail_layout(e)
        {
            const mail_layout_id = $(e).val();
            $("#mail_layout_header > p").addClass("d-none");
            $("#mail_layout_header > p.mail-layout-id-"+mail_layout_id).removeClass('d-none');
            $("#mail_layout_footer > p").addClass("d-none");
            $("#mail_layout_footer > p.mail-layout-id-"+mail_layout_id).removeClass('d-none');
        }

        change_mail_layout($("select[name=mail_layout_id]")[0]);

        function mail_preview(addr, action)
        {
            if(addr && action) {
                if (confirm(addr + "に送信しますか？")) {
                    const $form = $("#mail_layout_edit_form");
                    $form.attr("action", action);
                    $form.submit();
                }
            }
        }
    </script>
@endpush
