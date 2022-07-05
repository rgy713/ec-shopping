@inject('orderStatusList', 'App\Common\KeyValueLists\OrderStatusList')

@extends('admin.layouts.main.contents')

@section('title') 受注管理 > 受注管理ツール > 決済処理中キャンセル @endsection

@section('contents')
    <form method="post" style="width: 100%;">
        {{csrf_field()}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        決済処理中キャンセル
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
                                <th>受注ステータス</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($toCancels)>0)
                                @foreach($toCancels as $one)
                                    <tr>
                                        <td>{{$one->id}}</td>
                                        <td>{{$one->name01}}{{$one->name02}}</td>
                                        <td>@datetime($one->created_at)</td>
                                        <td>{{$orderStatusList[$one->order_status_id]}}</td>
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
                @if(count($toCancels)>0)
                    <button class="btn btn-sm btn-primary btn-block" type="submit"><i class="{{__('admin.icon_class.update')}}"></i>&nbsp;処理実行</button>
                @endif
            </div>
        </div>
    </form>
@endsection