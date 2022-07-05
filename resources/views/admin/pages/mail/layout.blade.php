@extends('admin.layouts.main.contents')

@section('title') {{__('admin.page_title.mail_layout')}} @endsection

@section('contents')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {{__('admin.page_header_name.mail_layout_list')}}
                    <div class="card-header-actions">
                        <a href="{{route("admin.mail.layout.create")}}"><button class="btn btn-sm">{{__('admin.operation.create')}}</button></a>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__('admin.item_name.mail_layout.name')}}</th>
                                <th>{{__('admin.item_name.mail_layout.remark')}}</th>
                                <th>{{__('admin.item_name.mail_layout.count')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($layouts as $layout)
                                <tr>
                                    <td>{{$layout->id}}</td>
                                    <td>{{$layout->name}}</td>
                                    <td>{{$layout->remark}}</td>
                                    <td>{{$layout->mailTemplates->count()}}</td>
                                    <td><a href="{{route("admin.mail.layout.edit",["id"=>$layout->id])}}"><button class="btn btn-sm"><i class="fa fa-edit"></i>&nbsp;詳細</button></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection

