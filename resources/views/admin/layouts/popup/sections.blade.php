{{-- baseレイアウトの各セクションに何を割り当てるかを定義する --}}
@extends('admin.layouts.popup.base')

{{-- ヘッダ --}}
@section('header')

@endsection

{{-- 左メニュー --}}
@section('left-menu')

@endsection

{{-- メインコンテンツ --}}
@section('main')

@endsection

{{-- 右メニュー --}}
@section('right-menu')

@endsection

{{-- フッタ --}}
@section('footer')
    @include('admin.components.common-footer')
@endsection
