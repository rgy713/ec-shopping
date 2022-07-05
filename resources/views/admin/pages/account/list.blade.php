@extends('admin.layouts.main.contents')
@section('title') 管理画面利用者 > 一覧 @endsection
@section('contents')

    <div class="row">
        <div class="col-12">
            @include('admin.components.account.list')
        </div>
    </div>
@endsection

