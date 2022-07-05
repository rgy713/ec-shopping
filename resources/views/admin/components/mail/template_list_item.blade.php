{{-- メールテンプレート一覧のデータ表示エリア --}}
<tr>
    {{-- テンプレートID --}}
    <td>{{$template->id}}</td>

    {{-- テンプレート名/件名 --}}
    <td>{{$template->name}}<br />
        {{$template->subject}}
    </td>

    {{-- レイアウト名 --}}
    <td>{{$template->layout->name}}</td>

    {{-- 送信契機 --}}
    <td>
        @if($template->mail_template_type_id===2)
            <a href="{{route("admin.mail.trigger",["id"=>$template->id])}}"><button class="btn btn-sm"><i class="fa fa-edit"></i>&nbsp;{{__('admin.item_name.common.setting')}}</button></a>
        @else
            {{$template->sending_trigger}}
        @endif
    </td>

    {{-- テンプレート編集 --}}
    <td>
        <a href="{{route("admin.mail.template.edit",["id"=>$template->id])}}"><button class="btn btn-sm"><i class="fa fa-edit"></i>&nbsp;{{__('admin.item_name.mail.template_edit')}}</button></a>
    </td>
</tr>
