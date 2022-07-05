<div class="card">
    <div class="card-header">
        {{__('admin.page_header_name.system_tax_list')}}
        <div class="card-header-actions">
        </div>
    </div>

    <div class="card-body">
        <table class="table table-sm table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>{{__('admin.item_name.tax.activated_from')}}</th>
                <th>{{__('admin.item_name.tax.rate')}}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($settings as $setting)
                @include('admin.components.system.tax_list_item',['setting' => $setting, 'count'=>count($settings), 'activedId'=>$activedId])
            @endforeach
            </tbody>
        </table>
    </div>
</div>