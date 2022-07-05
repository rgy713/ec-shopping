<tr class="">
    <td><a href="{{route('admin.media.edit', ["id"=>$media->id])}}">{{$media->code}}</a></td>
    <td class="d-none d-xl-table-cell">{{$media->broadcaster}}</td>
    <td>{{$media->name}}</td>
    <td class="d-none d-xl-table-cell">{{$media->cost}}{{__("admin.item_name.common.wen")}}</td>
    <td>{{$media->date}}</td>
    <td class="d-none d-xl-table-cell">{{$media->start_time}}</td>
    <td class="d-none d-xl-table-cell">{{$media->broadcast_minutes}}</td>
    <td class="d-none d-xl-table-cell">{{$media->broadcast_duration_from}}</td>
    <td class="d-none d-xl-table-cell">{{$media->item_lineup_id}}</td>
    <td>{{$media->customer_count}}{{__("admin.item_name.product.man_unit")}}</td>
    @if (isset($editable) && $editable)
        <td>
            <button @click.prevent="delete_item({{$media->id}})" class="btn btn-sm btn-secondary" @if ($media->customer_count > 0) disabled @endif>
                <i class="fa fa-remove"></i>&nbsp;{{__("admin.operation.delete")}}
            </button>
        </td>
    @endif
</tr>