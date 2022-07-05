{{-- メール配信履歴 --}}
<tr>
    {{-- 送信履歴ID --}}
    <td>{{$mail->id}}</td>
    {{-- 送信日時 --}}
    <td>@datetime($mail->created_at)</td>
    {{-- メールテンプレート名 --}}
    <td>{{$mail->mailTemplate->name}}</td>
    {{-- 件名 --}}
    <td>
        <a data-toggle="collapse" data-target="#mail-body-{{$mail->id}}" role="button" aria-expanded="false" aria-controls="mail-body-{{$mail->id}}">
            {{$mail->subject}}
        </a>
    </td>

</tr>

{{-- 本文、デフォルトでは非表示、クリック時に展開 --}}
<tr class="collapse" id="mail-body-{{$mail->id}}">
    <td colspan="4">
        <div data-spy="scroll" style="position: relative; height: 200px; overflow: auto; margin-top: .5rem; overflow-y: scroll;">
            {!! nl2br($mail->body) !!}
        </div>
    </td>
</tr>