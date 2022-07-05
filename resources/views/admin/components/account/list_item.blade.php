@inject('adminRoleList', 'App\Common\KeyValueLists\AdminRoleList')

{{-- 管理画面利用者を表示 --}}
<tr class="@if(!$account->enabled) bg-secondary @endif">
    {{-- ID --}}
    <td class="d-none d-lg-table-cell">{{$account->id}}</td>
    {{-- 名前 --}}
    <td>{{$account->name}}</td>
    {{-- 所属 --}}
    <td class="d-none d-lg-table-cell">{{$account->department}}</td>
    {{-- account --}}
    <td>{{$account->account}}</td>
    {{-- 権限 --}}
    <td>{{$adminRoleList[$account->admin_role_id]}}</td>
    {{-- 最終ログイン日時 --}}
    <td class="d-none d-lg-table-cell">@if($account->last_login)@datetime(\Carbon\Carbon::parse($account->last_login))@endif</td>

    @if($admin->admin_role_id==1 or $admin->admin_role_id==2)
        {{-- 編集 --}}
        <td><a href="{{route("admin.account.edit",['id'=>$account->id])}}"><button class="btn btn-sm btn-secondary"><i class="fa fa-edit"></i>&nbsp;{{__("admin.operation.edit")}}</button></a></td>

        {{-- 設定 --}}
        <td>
            @if($account->enabled)
                <a href="javascript:change_enabled('{{route("admin.account.disable")}}', {{$account->id}});"><button class="btn btn-sm btn-danger"><i class="fa fa-ban"></i>&nbsp;{{__("admin.operation.disable")}}</button></a>
            @else
                <a href="javascript:change_enabled('{{route("admin.account.enable")}}',{{$account->id}});"><button class="btn btn-sm btn-primary"><i class="fa fa-user-plus"></i>&nbsp;{{__("admin.operation.enable")}}</button></a>
            @endif

        </td>
    @endif
</tr>