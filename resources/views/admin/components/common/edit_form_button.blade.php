{{-- 登録フォーム用のボタンエリア --}}
<div class="row">
    <div class="col-6">
        <a class="btn btn-sm btn-block btn-secondary" tabindex="-1" href="@if(isset($back_route)){{$back_route}}@endif">
            <i class="{{__('admin.icon_class.back')}}"></i>&nbsp;{{__('admin.operation.back')}}
        </a>
    </div>
    <div class="col-6">
        <button class="btn btn-sm btn-block btn-primary">{{__("admin.operation.confirm_to")}}</button>
    </div>
</div>
