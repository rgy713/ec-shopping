{{-- メール配信履歴 --}}
<div class="card">
    <div class="card-header">
        履歴
        <div class="card-header-actions">

        </div>
    </div>

    <div class="card-body collapse multi-collapse show">
        <table class="table table-sm">
            <thead>
            <tr>
                <th>{{__("admin.item_name.mail.log_id")}}</th>
                <th>{{__("admin.item_name.mail.sent_at")}}</th>
                <th>{{__("admin.item_name.mail.template_name")}}</th>
                <th>{{__("admin.item_name.mail.subject")}}</th>
            </tr>
            </thead>

            <tbody>
                @each('admin.components.mail.history_item', $mails, 'mail', 'admin.components.mail.history_empty')
            </tbody>
        </table>
    </div>
</div>

