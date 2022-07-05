@extends('admin.layouts.main.contents')

@section('title') @if (isset($media)){{__('admin.page_title.media_edit')}}@else{{__('admin.page_title.media_create')}}@endif @endsection
@section('contents')
    <form method="POST" accept-charset="UTF-8">
        {{ csrf_field() }}
        <div class="row">

            {{-- 編集フォーム start --}}
            <div class="col-12">
                @include('admin.components.media.edit_form')
            </div>
            {{-- 編集フォーム end --}}

            {{-- ボタン表示 start --}}
            <div class="col-12">
                @include("admin.components.common.edit_form_button", ['back_route'=>route('admin.media.search', ['back'=>true])])
            </div>
            {{-- ボタン表示 end --}}
        </div>
    </form>
@endsection

