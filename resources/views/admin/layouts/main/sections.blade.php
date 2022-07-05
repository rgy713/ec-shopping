{{-- baseレイアウトの各セクションに何を割り当てるかを定義する --}}
@extends('admin.layouts.main.base')

{{-- ヘッダ --}}
@section('header')
    @include('admin.components.common-header')
@endsection

{{-- 左メニュー --}}
@section('left-menu')
    @include('admin.components.common-left-menu')
@endsection

{{-- メインコンテンツ --}}
@section('main')

@endsection

{{-- 右メニュー --}}
@section('right-menu')
    @include('admin.components.common-right-menu')
@endsection

{{-- フッタ --}}
@section('footer')
    @include('admin.components.common-footer')
@endsection
