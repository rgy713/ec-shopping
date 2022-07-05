@extends('admin.layouts.main.contents')

@section('title') 受注管理 > 受注管理ツール > 更新対象確認 @endsection

@section('contents')
    <form method="post" style="width: 100%;">
        {{csrf_field()}}
        <div class="row">
            <div class="col-lg-6 col-12">
                <div class="card">
                    <div class="card-header">
                        1.（新規受付、定期受付）→ 取り込み予約　更新対象：{{count($toImport)}}件
                        <div class="card-header-actions">
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>受注ID</th>
                                    <th>氏名</th>
                                    <th>受注日時</th>
                                    <th>お届け日</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(count($toImport))
                            @foreach($toImport as $one)
                                <tr>
                                    <td>{{$one->id}}</td>
                                    <td>{{$one->name01}}{{$one->name02}}</td>
                                    <td>@datetime($one->created_at)</td>
                                    <td>{{$one->requested_delivery_date}}</td>
                                </tr>
                            @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="card">
                    <div class="card-header">
                        2.取り込み予約 → 新規受付　更新対象：{{count($toNew)}}件
                        <div class="card-header-actions">
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>受注ID</th>
                                <th>氏名</th>
                                <th>受注日時</th>
                                <th>お届け日</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($toNew))
                            @foreach($toNew as $one)
                                <tr>
                                    <td>{{$one->id}}</td>
                                    <td>{{$one->name01}}{{$one->name02}}</td>
                                    <td>@datetime($one->created_at)</td>
                                    <td>{{$one->requested_delivery_date}}</td>
                                </tr>
                            @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <a href="{{route("admin.order.utility")}}"><button class="btn btn-sm btn-secondary btn-block" type="button"><i class="{{__('admin.icon_class.back')}}"></i>&nbsp;{{__('admin.operation.back')}}</button></a>
            </div>
            <div class="col-6">
                @if(count($toNew) > 0 || count($toImport) > 0)
                    <button class="btn btn-sm btn-primary btn-block" type="submit"><i class="{{__('admin.icon_class.update')}}"></i>&nbsp;ステータス更新</button>
                @endif
            </div>
        </div>
    </form>
@endsection