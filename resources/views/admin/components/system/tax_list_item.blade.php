@inject('carbon', 'Carbon\Carbon')

<tr class="@if(isset($activedId) && $setting->id == $activedId)table-primary @endif">
    <td>{{$setting->id}}</td>
    <td>@datetime($carbon->parse($setting->activated_from))～</td>
    <td>@rateToPercent($setting->rate)%</td>
    @if(($admin->admin_role_id==1 or $admin->admin_role_id==2) and $count>1)
        <td><button class="btn btn-sm btn-secondary" onclick="tax_delete({{$setting->id}});"><i class="fa fa-trash"></i>&nbsp;{{__("admin.operation.delete")}}</button></td>
    @endif
</tr>
