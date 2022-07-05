@extends('admin.layouts.main.contents')
@section('title') {{__("admin.page_title.product_edit")}} @endsection
@section('contents')
    <form method="POST" enctype="multipart/form-data"  accept-charset="UTF-8">
        {{ csrf_field() }}
        <div class="row">
            {{-- 入力フォーム start --}}
            <div class="col-12">
                @include("admin.components.product.detail")
            </div>
            {{-- 入力フォーム end --}}

        {{-- ボタン --}}
        <div class="col-12">
            <div class="row mb-3">
                <div class="col-6">
                    <a class="btn btn-sm btn-block btn-secondary" tabindex="-1" href="{{route('admin.product.search', ['back'=>true])}}">
                        <i class="{{__('admin.icon_class.back')}}"></i>&nbsp;{{__('admin.operation.back')}}
                    </a>
                </div>

                <div class="col-6">
                    <button class="btn btn-sm btn-block btn-primary">
                        <i class="{{__('admin.icon_class.save')}}"></i>&nbsp;{{__('admin.operation.save')}}
                    </button>
                </div>
            </div>
        </div>
        {{-- ボタン --}}

        </div>
    </form>
@endsection

