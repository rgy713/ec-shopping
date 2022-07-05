@inject('itemLineupList', 'App\Common\KeyValueLists\ItemLineupList')
@inject('enabledDisabledList', 'App\Common\KeyValueLists\EnabledDisabledList')

@extends('admin.layouts.main.contents')
@section('title') {{__("admin.page_title.asp_edit")}} @endsection

@section('contents')
    <div class="row" id="edit-body">
        @include('admin.pages.media.asp_search')

        @include('admin.pages.media.asp_create')
    </div>

    <form id="creat_asp_form" method="POST" accept-charset="UTF-8" action="{{route('admin.media.asp_create_batch')}}">
        {{ csrf_field() }}
    </form>
@endsection

