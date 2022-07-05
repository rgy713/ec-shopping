@extends('admin.layouts.main.contents')

@section('title') {{__('admin.page_title.mail_template')}} @endsection

@section('contents')
    <div class="row">
        {{-- システム系 start --}}
        <div class="col-12">
            @include('admin.components.mail.template_type1_list',["templates"=>$system_templates])
        </div>
        {{-- システム系 end --}}

        {{-- 自動配信バッチ系 start --}}
        <div class="col-12">
            @include('admin.components.mail.template_type2_list',["templates"=>$step_templates])
        </div>
        {{-- 自動配信バッチ系 end --}}

    </div>
@endsection

