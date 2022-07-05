<div class="card">
    <div class="card-header">
        {{__('admin.page_header_name.step_mail_template')}}
        <div class="card-header-actions">
            <div class="card-header-actions">
                <a href="{{route("admin.mail.template.create",["type"=>2])}}"><button class="btn btn-sm">新規作成</button></a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-sm">
            <thead>
            <tr>
                <th>#</th>
                <th>{{__("admin.item_name.mail.template_name")}}</th>
                <th>{{__("admin.item_name.mail.layout")}}</th>
                <th>{{__("admin.item_name.mail.trigger")}}
                <th>{{__('admin.operation.edit')}}</th>
            </tr>
            </thead>

            <tbody>
            @each('admin.components.mail.template_list_item', $templates,'template')
            </tbody>
        </table>
    </div>
</div>
