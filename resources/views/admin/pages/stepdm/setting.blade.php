@extends('admin.layouts.main.contents')

@section('title') {{__('admin.page_title.stepdm_setting_view')}} @endsection

@section('contents')
    <div class="row">
        <div class="col-12">
            <span class="specification" data-toggle="popover" data-placement="right" data-content="ほぼ現行仕様通り。（列の順序は一部変更し、DMコードを2列目に移動）off の行は背景色グレー。">&nbsp;</span>

            <div class="card">
                <div class="card-header">
                    {{__('admin.page_header_name.stepdm_setting')}}
                    <div class="card-header-actions">
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-sm table-hover">
                        <thead>
                        <tr>
                            <th>{{__('admin.item_name.dm.id')}}</th>
                            <th>{{__('admin.item_name.dm.code')}}</th>
                            <th>{{__('admin.item_name.dm.target')}}{{__("admin.item_name.product.name")}}</th>
                            <th>{{__('admin.item_name.dm.target')}}{{__("admin.item_name.product.id")}}</th>
                            <th>{{__('admin.item_name.dm.req_periodic_count')}}</th>
                            <th>{{__('admin.item_name.dm.req_elapsed_days_from_sending_out')}}</th>
                            <th>{{__('admin.item_name.dm.is_active')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($stepdm_settings as $setting)
                            <tr class="@if(!$setting->is_active)table-secondary @endif">
                                <td>{{$setting->id}}</td>
                                <td>{{$setting->code}}</td>
                                <td>{{$setting->product_name}}</td>
                                <td>{{$setting->product_id}}</td>
                                <td>{{$setting->req_periodic_count}}</td>
                                <td>{{$setting->req_elapsed_days_from_sending_out}}{{__('admin.item_name.dm.days_later')}}</td>
                                <td>@if($setting->is_active)on @else off @endif</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    {{__('admin.page_header_name.stepdm_item_setting')}}
                    <div class="card-header-actions">
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-sm table-hover">
                        <thead>
                        <tr>
                            <th>{{__('admin.item_name.dm.bundle_item_id')}}</th>
                            <th>{{__('admin.item_name.dm.target')}}{{__("admin.item_name.product.name")}}</th>
                            <th>{{__('admin.item_name.dm.target')}}{{__("admin.item_name.product.id")}}</th>
                            <th>{{__('admin.item_name.dm.req_periodic_count')}}</th>
                            <th>{{__('admin.item_name.dm.bundle')}}{{__("admin.item_name.product.name")}}</th>
                            <th>{{__('admin.item_name.dm.bundle')}}{{__("admin.item_name.product.id")}}</th>
                            <th>{{__('admin.item_name.dm.quantity')}}</th>
                            <th>{{__('admin.item_name.dm.enabled')}}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($item_settings as $setting)
                            <tr class="@if(!$setting->enabled)table-secondary @endif">
                                <td>{{$setting->id}}</td>
                                <td>{{$setting->req_product_name}}</td>
                                <td>{{$setting->req_product_id}}</td>
                                <td>{{$setting->req_periodic_count}}</td>
                                <td>{{$setting->product_name}}</td>
                                <td>{{$setting->product_id}}</td>
                                <td>{{$setting->quantity}}{{__('admin.item_name.dm.point')}}</td>
                                <td>@if($setting->enabled)on @else off @endif</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

