@extends('admin.layouts.main.contents')
@section('title') {{__("admin.page_title.product_create")}} @endsection
@section('contents')
    <form method="POST" enctype="multipart/form-data"  accept-charset="UTF-8">
        {{ csrf_field() }}
        <div class="row mb-5">
            {{-- 入力フォーム start --}}
            <div class="col-12">
                @include("admin.components.product.detail",["form_title"=>__('admin.page_header_name.product_create')])
            </div>
            {{-- 入力フォーム end --}}

            {{-- ボタン --}}
            @if (isset($product))
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
            @else
                <div class="col-12">
                    <div class="row mb-3">
                        <div class="col-6 offset-3">
                            <button class="btn btn-sm btn-block btn-primary">
                                <i class="{{__('admin.icon_class.save')}}"></i>&nbsp;{{__('admin.operation.save')}}
                            </button>
                        </div>
                    </div>
                </div>
            @endif
            {{-- ボタン --}}

        </div>
    </form>

@endsection

