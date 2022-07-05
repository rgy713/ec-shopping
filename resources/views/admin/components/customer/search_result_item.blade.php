{{-- 顧客検索結果の表示 --}}
<tr>
    <td>{{str_replace('会員','',$customerStatusList[$customer->customer_status_id])}}</td>
    <td>
        <a href="{{route("admin.customer.info",['id'=>$customer->id])}}">{{$customer->id}}</a><br>
        {{$prefectureList[$customer->prefecture_id] or ""}}
    </td>

    <td>
        <a href="{{route("admin.customer.info",['id'=>$customer->id])}}">{{$customer->name01.$customer->name02}}</a><br>
        ({{$customer->kana01.$customer->kana02}})
    </td>

    <td>
        {{$customer->phone_number01 or ""}}<br>
        {{$customer->phone_number02 or ""}}
    </td>

    <td>
        {{$customer->email}}
    </td>

    <td class="d-none d-xl-table-cell">
        {{$customer->birthday or ""}}
    </td>
    <td class="d-none d-xl-table-cell">
        {{$customer->buy_times or "0"}}回<br>
        {{$customer->buy_volume or "0"}}本
    </td>
    <td class="d-none d-xl-table-cell">
        {{$customer->advertising_media_code or ""}}
    </td>

    <td>
        @if($admin->admin_role_id==1 or $admin->admin_role_id==2)
            <button class="btn btn-sm btn-secondary" type="button" @click.prevent="deleteCustomer({{$customer->id}})" ><i class="fa fa-trash"></i>&nbsp;{{__("admin.operation.delete")}}</button>
        @endif
    </td>
</tr>
