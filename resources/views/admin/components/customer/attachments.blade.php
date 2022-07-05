<div class="card">
    <div class="card-header"><i class="fa fa-file"></i>&nbsp;添付一覧　{{count($attachments)}}件
        <div class="card-header-actions">
            <button type="button" class="btn btn-sm btn-block"
                    data-customer_id="{{$customer->id}}"
                    onclick="modal_attachment_form.open(this)"
            ><i class="fa fa-upload"></i> アップロード</button>
        </div>
    </div>
    <div class="card-body">
        <div class="data-spy" data-spy="scroll" data-offset="65" style="position: relative; height: 117px; overflow: auto; margin-top: .5rem; overflow-y: scroll;">
            <table class="table table-sm">
                <tbody>
                @foreach($attachments as $attachment)
                <tr>
                    {{--TODO file path setting--}}
                    <td><a href="#" onClick="popupFileViewer('{{asset('storage/'.$attachment->file_path)}}')">{{$attachment->title}}</a>

                    </td>
                    <td>@datetime($attachment->created_at)</td>
                    <td>
                        @if($admin->admin_role_id==1 or $admin->admin_role_id==2)
                            <button class="btn btn-sm btn-block" type="button" onclick="deleteAttachment({{$attachment->id}})">{{__("admin.operation.delete")}}</button>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- modal:アップロード --}}
<div id="modal_attachment_form" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">添付ファイルのアップロード</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.customer.attachment.upload')}}" class="form-horizontal" method="POST" enctype="multipart/form-data" @submit.prevent="onSubmit">
                {{csrf_field()}}
                <input type="hidden" name="customer_id" v-model="customer_id">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            {{-- 件名 --}}
                            <div class="row form-group">
                                <label class="col-3 col-form-label form-control-sm">件名</label>

                                <div class="col-9 input-group-sm">
                                    <input type="text" :class="['form-control form-control-sm', errors.file_title ? 'is-invalid' : '']" name="file_title" placeholder="" value="{{old('file_title')}}" required>
                                    <div v-if="errors.file_title" class="invalid-feedback">@{{  errors.file_title[0] }}</div>
                                </div>
                            </div>

                            {{-- 対応状況 --}}
                            <div class="row form-group">
                                <label class="col-3 col-form-label form-control-sm">ファイル</label>

                                <div class="col-9 input-group-sm">
                                    <input type="file" :class="['form-control form-control-sm', errors.file_content ? 'is-invalid' : '']" name="file_content" accept=".jpg, .gif, .png, .pdf" required>
                                    <div v-if="errors.file_content" class="invalid-feedback">@{{  errors.file_content[0] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('admin.operation.close')}}</button>
                    <button type="submit" class="btn btn-primary">{{__('admin.operation.save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="delete_attachment_form" method="POST" action="{{route('admin.customer.attachment.delete')}}">
    {{ csrf_field() }}
    <input type="hidden" name="attachment_id">
</form>

@push('content_js')
    <script>
        var modal_attachment_form = new Vue({
            el:'#modal_attachment_form',
            data:function(){
                return{
                    customer_id:'',
                    errors:{},
                }
            },
            methods:{
                showModal:function(){
                    $("#"+this.$el.id).modal("show");
                },
                open:function(e){
                    this.customer_id = $(e).data('customer_id');
                    this.showModal();
                },
                onSubmit:function(e) {
                    const action = e.target.action;
                    const dataform = new FormData(e.target);
                    axios.post(action, dataform)
                        .then( response => {
                            if(response.data.status=="error"){
                                this.errors = response.data.saved;
                            }else{
                                this.errors = {};
                                window.location.reload();
                            }
                        })
                        .catch((error) => {
                            this.errors = error.response.data.errors;
                        });
                }
            }
        });

        function popupFileViewer(src){
            const FileViewer = window.open("","wildebeast","width=900,height=600,scrollbars=1,resizable=1");
            var html="";
            if(src.indexOf(".pdf")>0){
                html = '<html><head></head><body><iframe width="100%" height="100%" src="'+ src +'"></iframe></body></html>';
            }
            else{
                html = '<html><head></head><body><image width="100%" src="'+ src +'"></body></html>';
            }
            FileViewer.document.open();
            FileViewer.document.write(html);
            FileViewer.document.close();
            return false;
        }

        function deleteAttachment(id){
            if(id) {
                if (confirm("削除しますか？")) {
                    const $form = $("form#delete_attachment_form");
                    $("form#delete_attachment_form input[name=attachment_id]").val(id);
                    $form.submit();
                }
            }
        }
    </script>
@endpush