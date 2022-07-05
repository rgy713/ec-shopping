@extends('admin.layouts.main.contents')

@section('title') {{__('admin.page_title.media_info')}} @endsection
@section('contents')
    <div class="row">

        {{-- 編集フォーム start --}}
        <div class="col-12">
            @include('admin.components.media.info')
        </div>
        {{-- 編集フォーム end --}}

        {{-- ボタン表示 start --}}
        <div class="col-12">
            <div class="row mb-3">
                <div class="col-6 offset-3">
                    <a class="btn btn-sm btn-block btn-secondary" tabindex="-1" href="{{route('admin.media.search', ['back'=>true])}}">{{__("admin.operation.back")}}</a>
                </div>
            </div>
        </div>
        {{-- ボタン表示 end --}}
    </div>
@endsection