{{-- modal3:失敗フラグ --}}
<div id="modal_periodic_failed_flag_form" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('admin.item_name.periodic.status')}}の変更</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.customer.periodic.fail.update')}}" method="POST" accept-charset="UTF-8" @submit.prevent="onSubmit">
                {{ csrf_field() }}
                <input type="hidden" name="periodic_order_id" v-model="periodic_order_id">
                <input type="hidden" name="updated_at" v-model="updated_at">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            {{--ta対応状況--}}
                            <div class="row form-group">
                                <label class="col-4 col-form-label form-control-sm">{{__('admin.item_name.periodic.status')}}</label>

                                <div class="col-8 input-group-sm">
                                    <input type="text" readonly class="form-control-plaintext form-control-sm" v-model="failed_flag_text">
                                    <input type="hidden" name="failed_flag" v-model="failed_flag">
                                </div>
                            </div>

                            {{-- shopmemo --}}
                            <div class="row mb-sm-3">
                                <label class="col-4 col-form-label form-control-sm">{{__("admin.item_name.common.shop_memo")}}(自動生成)</label>
                                <div class="col-8">
                                    <textarea class="form-control form-control-sm" name="shop_memo_auto" rows="1" placeholder="" readonly>{{__('admin.item_name.periodic.status')}}を@{{auto_shopmemo}}</textarea>
                                </div>
                            </div>
                            <div class="row mb-sm-3">
                                <label class="col-4 col-form-label form-control-sm">{{__("admin.item_name.common.shop_memo")}}(自由入力)</label>
                                <div class="col-8">
                                    <textarea class="form-control form-control-sm" name="shop_memo_body" rows="3" placeholder=""></textarea>
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

@push('content_js')
    <script>
        var modal_periodic_failed_flag_form = new Vue({
            el:'#modal_periodic_failed_flag_form',
            data:function(){
                return{
                    failed_flag:'',
                    failed_flag_text:'',
                    updated_at:'',
                    periodic_order_id:'',
                    auto_shopmemo:'',
                    errors:{},
                }
            },
            methods:{
                showModal:function(){
                    $("#"+this.$el.id).modal("show");
                },
                open:function(e){
                    this.failed_flag = $(e).data('failed_flag') ? 1 : 0;
                    this.periodic_order_id = $(e).data('periodic_order_id');
                    this.updated_at = $(e).data('updated_at');
                    this.failed_flag_text = this.failed_flag ? '失敗→正常' : '正常→失敗';
                    this.auto_shopmemo = this.failed_flag ? '失敗から正常へ変更' : '正常から失敗へ変更';
                    this.showModal();
                },
                onSubmit:function(e) {
                    var action = e.target.action;
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
    </script>
@endpush