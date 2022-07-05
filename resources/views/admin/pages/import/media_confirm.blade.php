@extends('admin.layouts.main.contents')
@section('title') CSVインポート > 広告媒体（確認） @endsection

@section('contents')
    <div class="row">
        <div class="col-12">
            @include('admin.components.import.media_confirm_list')
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @include('admin.components.import.media_confirm_excluded')
        </div>
    </div>

    <div class="col-12">
        <div class="row">
            <div class="col-6">
                <button class="btn btn-sm btn-block btn-secondary" tabindex="-1">キャンセル</button>
            </div>
            <div class="col-6">
                <button class="btn btn-sm btn-block btn-primary">インポート実行</button>
            </div>
        </div>
    </div>

@endsection

