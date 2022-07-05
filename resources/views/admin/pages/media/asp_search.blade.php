{{-- ASP一覧 --}}
<div class="col-12">
    <form id="search_form" method="POST" accept-charset="UTF-8" action="{{route('admin.media.asp_edit')}}">
        {{csrf_field()}}

        <div class="card">

            <div class="card-header">
                {{__('admin.page_header_name.asp_edit')}}
                <div class="card-header-actions">
                </div>
            </div>

            <div class="card-body">
                <table class="table table-sm table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('admin.item_name.asp.name')}}</th>
                        <th>{{__('admin.item_name.asp.remark1')}}</th>
                        <th>{{__("admin.item_name.media.cost")}}</th>
                        <th>{{__("admin.item_name.product.lineup")}}</th>
                        <th>{{__("admin.item_name.common.enabled_disabled")}}</th>
                        <th>{{__('admin.item_name.asp.edit')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($asp_medias as $count => $asp_media)
                        <tr>
                            <input name="asp_id[]" type="hidden" value="{{$asp_media['id']}}">
                            <td>{{$asp_media['id']}}</td>
                            <td>{{$asp_media['name']}}</td>
                            <td>
                                <input name="asp_remark1[{{$asp_media['id']}}]" class="form-control form-control-sm" type="text"
                                       value="{{$asp_media['remark1']}}" onchange="return app.functions.trim(this);"/>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input name="asp_default_cost[{{$asp_media['id']}}]" class="form-control form-control-sm" type="number" min="0"  max="2147483647" step="1"  onchange="return app.functions.trim(this);"  onkeydown="app.functions.only_number_key(event);"
                                           value="{{$asp_media['default_cost']}}" required/>
                                    <span class="input-group-append">
                                        <button class="btn btn-default btn-sm" type="button">{{__("admin.item_name.common.wen")}}</button>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <select name="asp_default_item_lineup_id[{{$asp_media['id']}}]" class="form-control form-control-sm" required>
                                    @foreach($itemLineupList as $id => $name)
                                        <option value="{{$id}}" @if($id===$asp_media['default_item_lineup_id']) selected @endif >{{$name}}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <select name="asp_enabled[{{$asp_media['id']}}]" class="form-control form-control-sm" required>
                                    @foreach($enabledDisabledList as $id => $name)
                                        <option value="{{$id}}" @if($id==$asp_media['enabled']) selected @endif >{{$name}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <button type="button" onclick="asp_create_batch({{$asp_media['id']}});" class="btn btn-sm btn-block btn-primary">{{__('admin.item_name.asp.edit')}}</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </form>
</div>

{{-- ボタン表示 start --}}
<div class="col-12 mb-4">
    <div class="row">
        <div class="offset-3 col-6">
            <button form="search_form" class="btn btn-sm btn-block btn-primary">{{__('admin.operation.update')}}</button>
        </div>
    </div>
</div>
{{-- ボタン表示 end --}}

@push('content_js')
    <script>
        function asp_create_batch(asp_id) {
            const data_form = new FormData($("#creat_asp_form")[0]);
            const form_action = $("#creat_asp_form").attr('action');
            data_form.set('asp_id', asp_id);
            axios.post(form_action, data_form)
                .then(response => {
                    if (response.data && response.data.status == 'success') {

                    }
                })
                .catch(error => {
                    console.log(error);
                });
        }
    </script>
@endpush