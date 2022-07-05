{{-- modal2:次回到着 --}}
<div id="modal_periodic_next_delivery_date_form" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('admin.item_name.periodic.next_create_date')}}の変更</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.customer.periodic.next.update')}}" method="POST" accept-charset="UTF-8" @submit.prevent="onSubmit">
                {{ csrf_field() }}
                <input type="hidden" name="periodic_order_id" v-model="periodic_order_id">
                <input type="hidden" name="updated_at" v-model="updated_at">
                <div class="modal-body">
                    <p>変更後の{{__('admin.item_name.periodic.next_create_date')}}を指定してください。</p>

                    <div class="card">
                        <div class="card-body">
                            {{-- 次回お届け日 --}}
                            <div class="row form-group">
                                <label class="col-4 col-form-label form-control-sm">{{__("admin.item_name.periodic.next_create_date")}}</label>

                                <div class="col-8 input-group-sm">
                                    <input type="date" :class="['form-control form-control-sm', errors.next_delivery_date ? 'is-invalid' : '']" name="next_delivery_date" v-model="next_delivery_date" v-on:change="changeAutoMemo" required>
                                    <div v-if="errors.date" class="invalid-feedback">@{{  errors.next_delivery_date[0] }}</div>
                                </div>
                            </div>

                            {{-- shopmemo --}}
                            <div class="row mb-sm-3">
                                <label class="col-4 col-form-label form-control-sm">{{__("admin.item_name.common.shop_memo")}}(自動生成)</label>
                                <div class="col-8">
                                    <textarea class="form-control form-control-sm" name="shop_memo_auto" rows="2" placeholder="" readonly>{{__('admin.item_name.periodic.next_create_date')}}を@{{auto_shopmemo}}</textarea>
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
        var modal_periodic_next_delivery_date_form = new Vue({
            el:'#modal_periodic_next_delivery_date_form',
            data:function(){
                return{
                    next_delivery_date:'',
                    old_next_delivery_date:'',
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
                changeAutoMemo:function(){
                    if(this.old_next_delivery_date != this.next_delivery_date)
                        this.auto_shopmemo = this.old_next_delivery_date + 'から' + this.next_delivery_date + 'に変更';
                    else
                        this.auto_shopmemo = this.next_delivery_date;
                },
                open:function(e){
                    this.next_delivery_date = $(e).data('next_delivery_date');
                    this.old_next_delivery_date = this.next_delivery_date;
                    this.periodic_order_id = $(e).data('periodic_order_id');
                    this.updated_at = $(e).data('updated_at');
                    this.auto_shopmemo = this.next_delivery_date;
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