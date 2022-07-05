@extends('admin.layouts.main.contents')
@section('title') {{__("admin.page_title.account_create")}} @endsection
@section('contents')
    <form method="POST" accept-charset="UTF-8">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-12">
                @include('admin.components.account.edit_form',["form_title"=>__('admin.page_header_name.account_create')])
            </div>
        </div>

        {{-- ボタン --}}
        <div class="col-12">
            <div class="row mb-3">
                <div class="col-6">
                    <a href="{{route("admin.account.list")}}">
                        <button class="btn btn-sm btn-block btn-secondary" tabindex="-1" type="button">
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
        {{-- ボタン --}}
    </form>
@endsection

