@inject('mailTemplateForOrderList', 'App\Common\KeyValueLists\MailTemplateForOrderList')
@inject('mailLayoutList', 'App\Common\KeyValueLists\MailLayoutList')

<div class="card" id="select_mail_template">
    <form method="POST" accept-charset="UTF-8">
        {{ csrf_field() }}
        <div class="card-header">
            <div class="row form-group">
                <label class="col-2 col-form-label col-form-label-sm">通知の送信</label>
                <div class="col-4">
                    <select class="form-control form-control-sm @isInvalid($errors,'mail_template_id')" name="mail_template_id" v-on:change="onChange" required v-model="mail_template_id">
                        <option></option>
                        @foreach($mailTemplateForOrderList as $id=>$name)
                            <option value="{{$id}}">{{$name}}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">{{$errors->first('mail_template_id')}}</div>
                </div>
            </div>

            <div class="card-header-actions">
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                {{-- 件名 --}}
                <div class="col-12">
                    <div class="row form-group">
                        <label class="col-2 col-form-label col-form-label-sm">{{__('admin.item_name.mail.subject')}}</label>
                        <div class="col-10">
                            <input type="text" class="form-control form-control-sm @isInvalid($errors,'subject')" name="subject" max="50" required v-model="subject">
                            <div class="invalid-feedback">{{$errors->first('subject')}}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="row form-group">
                        <label class="col-2 col-form-label col-form-label-sm">{{__('admin.item_name.mail.body')}}/ヘッダ</label>
                        <div class="col-10">
                            <p class="text-muted" v-html="header">
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="row form-group">
                        <label class="col-2 col-form-label col-form-label-sm">{{__('admin.item_name.mail.body')}}/動的出力部分</label>
                        <div class="col-10">
                            <p class="text-muted" v-html="body">
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="row form-group">
                        <label class="col-2 col-form-label col-form-label-sm">{{__('admin.item_name.mail.body')}}/フッタ</label>
                        <div class="col-10">
                            <p class="text-muted" v-html="footer">
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-12 col-lg-12">
                    <button class="btn btn-sm btn-block btn-primary" type="submit" v-bind:hidden="!mail_template_id">送信</button>
                </div>
            </div>
        </div>
    </form>
    <form id="get_template_form" method="POST" accept-charset="UTF-8" action="{{route('admin.mail.send.order.get_template')}}">
        {{ csrf_field() }}
    </form>
</div>
@push('content_js')
    <script>
        const select_mail_template = new Vue({
            el: '#select_mail_template',
            data: function(){
                return {
                    errors:{},
                    mail_template_id:'',
                    subject:'',
                    header:'',
                    body:'',
                    footer:'',
                }
            },
            methods : {
                onChange(event) {
                    const template_id = event.target.value;
                    this.getMailTemplate(template_id);
                },
                getMailTemplate:function(template_id){
                    const form = $("#get_template_form")[0];
                    var action = form.action;
                    const dataform = new FormData(form);
                    dataform.append('mail_template_id',template_id);
                    axios.post(action, dataform)
                        .then( response => {
                            if(response.data.status=="error"){
                                this.errors = response.data.saved;
                            }else{
                                this.errors = {};
                                const data = response.data.saved;
                                this.subject = data.subject;
                                this.header = data.header;
                                this.body = data.body;
                                this.footer = data.footer;
                            }
                        })
                        .catch((error) => {
                            this.errors = error.response.data.errors;
                        });
                },
            }
        });
    </script>
@endpush