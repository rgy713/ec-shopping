<div class="card">
    <div class="card-header">
        {{__('admin.page_header_name.system_holiday')}}
        @if($admin->admin_role_id==1 or $admin->admin_role_id==2)
            <div class="card-header-actions">
                <a class="card-header-action btn-close" href="#">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal_holiday_regist_form">&nbsp;{{__("admin.operation.create")}}</button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="holidays_delete('{{route("admin.system.holiday.delete")}}')">&nbsp;{{__("admin.operation.delete")}}</button>
                </a>
            </div>
        @endif
    </div>

    <div class="card-body">
        <table class="table table-sm table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>{{__('admin.item_name.holiday.date')}}</th>
                <th></th>
                <th>{{__('admin.item_name.holiday.name')}}</th>
                <th>{{__("admin.operation.delete")}}</th>
            </tr>
            </thead>
            <tbody>
            @each('admin.components.system.holiday_edit_form_item',$holidays,'holiday')
            </tbody>
        </table>
    </div>
</div>