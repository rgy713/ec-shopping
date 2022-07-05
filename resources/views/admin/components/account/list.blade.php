<div class="card">
    <div class="card-header">
        {{__("admin.page_header_name.account_list")}}
        <div class="card-header-actions">
        </div>
    </div>
    <div class="card-body">
        <table class="table table-sm">
            <thead>
            <tr>
                {{-- ID --}}
                <th class="d-none d-lg-table-cell">{{__("admin.item_name.account.id")}}</th>
                {{-- 名前 --}}
                <th>{{__("admin.item_name.account.name")}}</th>
                {{-- 所属 --}}
                <th class="d-none d-lg-table-cell">{{__("admin.item_name.account.department")}}</th>
                {{-- email --}}
                <th>{{__("admin.item_name.account.account")}}</th>
                {{-- 権限 --}}
                <th>{{__("admin.item_name.account.authority")}}</th>
                {{-- 最終ログイン日時 --}}
                <th class="d-none d-lg-table-cell">{{__("admin.item_name.account.last_login")}}</th>
                @if($admin->admin_role_id==1 or $admin->admin_role_id==2)
                    {{-- 編集 --}}
                    <th>{{__("admin.operation.edit")}}</th>
                    {{-- 削除 --}}
                    <th>{{__("admin.item_name.common.setting")}}</th>
                @endif
            </tr>
            </thead>

            <tbody>
            @each('admin.components.account.list_item', $accounts, 'account')
            </tbody>
        </table>
    </div>
</div>
<form id="changeEnabled" method="POST" action="">
    {{ csrf_field() }}
    <input type="hidden" name="id">
</form>
{{--TODO--}}
<script>
    function change_enabled(action, id){
        if(action && id) {
            const  $form = $("form#changeEnabled");
            $form.attr("action", action);
            $("form#changeEnabled input[name=id]").val(id);
            $form.submit();
        }
    }
</script>