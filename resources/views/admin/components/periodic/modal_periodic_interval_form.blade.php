{{-- modal:定期間隔変更 --}}
<div id="modal_periodic_interval_form" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('admin.item_name.periodic.interval')}}の変更</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.customer.periodic.interval.update')}}" method="POST" accept-charset="UTF-8" @submit.prevent="onSubmit">
                {{ csrf_field() }}
                <input type="hidden" name="periodic_order_id" v-model="periodic_order_id">
                <input type="hidden" name="updated_at" v-model="updated_at">
                <div class="modal-body">
                    <p>変更内容を選択してください。</p>
                    <div class="card">
                        <div class="card-body">
                            {{-- 1.日数指定 --}}
                            <div class="row form-group">
                                <label class="col-4 col-form-label form-control-sm">{{__('admin.item_name.periodic.interval')}}</label>
                            </div>

                            <div class="row form-group">
                                <div class="col-4">
                                    <div class="form-check form-check-inline form-control-sm">
                                        <input name="periodic_interval_type_id" class="form-check-input" type="radio" v-model="periodic_interval_type_id" v-bind:value="1" v-on:change="changeAutoMemo">
                                        <label class="form-check-label" for="">日数指定</label>
                                    </div>
                                </div>

                                <div class="col-4 input-group-sm">
                                    <div class="input-group-prepend">
                                        <input class="form-control form-control-sm" name="interval_days" type="number" placeholder="" v-model="interval_days" v-on:change="changeAutoMemo" min="10" max="120" :readonly="periodic_interval_type_id != 1" :required="periodic_interval_type_id == 1">
                                        <span class="input-group-text">日間隔</span>
                                    </div>
                                </div>
                            </div>

                            {{-- 2.◯ヶ月ごと指定日 --}}
                            <div class="row form-group">
                                <div class="col-4">
                                    <div class="form-check form-check-inline form-control-sm">
                                        <input name="periodic_interval_type_id" value="2" class="form-check-input" type="radio" v-model="periodic_interval_type_id" v-bind:value="2" v-on:change="changeAutoMemo">
                                        <label class="form-check-label" for="">月数/日付指定</label>
                                    </div>
                                </div>

                                <div class="col-4 input-group-sm">
                                    <div class="input-group-prepend">
                                        <input class="form-control form-control-sm" name="interval_month" type="number" placeholder="" v-model="interval_month" v-on:change="changeAutoMemo" min="1" max="6" :readonly="periodic_interval_type_id != 2"  :required="periodic_interval_type_id == 2">
                                        <span class="input-group-text">ヶ月毎</span>
                                    </div>
                                </div>

                                <div class="col-3 input-group-sm ml-4">
                                    <div class="input-group-prepend">
                                        <input class="form-control form-control-sm" name="interval_date_of_month" type="number" placeholder="" v-model="interval_date_of_month" v-on:change="changeAutoMemo" min="1" max="28" :readonly="periodic_interval_type_id != 2"  :required="periodic_interval_type_id == 2">
                                        <span class="input-group-text">日</span>
                                    </div>
                                </div>
                            </div>

                            {{-- shopmemo --}}
                            <div class="row mb-sm-3">
                                <label class="col-4 col-form-label form-control-sm">{{__("admin.item_name.common.shop_memo")}}(自動生成)</label>
                                <div class="col-8">
                                    <textarea class="form-control form-control-sm" name="shop_memo_auto" rows="1" placeholder="" readonly>{{__('admin.item_name.periodic.interval')}}を@{{ auto_shopmemo }}</textarea>
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
        var modal_periodic_interval_form = new Vue({
            el:'#modal_periodic_interval_form',
            data:function(){
                return{
                    periodic_interval_type_id:'',
                    old_val:'',
                    interval_days:10,
                    interval_month:1,
                    interval_date_of_month:1,
                    auto_shopmemo:'',
                    old_auto_shopmemo:'',
                    updated_at:'',
                    periodic_order_id:'',
                    errors:{},
                }
            },
            methods:{
                showModal:function(){
                    $("#"+this.$el.id).modal("show");
                },
                changeAutoMemo:function(){
                    if(this.periodic_interval_type_id === 1){
                        if(this.old_val != [this.periodic_interval_type_id,this.interval_days].join("-"))
                            this.auto_shopmemo = this.old_auto_shopmemo + "から" + this.interval_days + '日間隔へ変更';
                        else
                            this.auto_shopmemo = this.old_auto_shopmemo;
                    }else if(this.periodic_interval_type_id === 2){
                        if(this.old_val != [this.periodic_interval_type_id,this.interval_month,this.interval_date_of_month].join("-"))
                            this.auto_shopmemo = this.old_auto_shopmemo + "から" + this.interval_month + 'ヶ月毎'+this.interval_date_of_month+'日へ変更';
                        else
                            this.auto_shopmemo = this.old_auto_shopmemo;
                    }
                },
                open:function(e){
                    this.periodic_interval_type_id = $(e).data('periodic_interval_type_id');
                    this.periodic_order_id = $(e).data('periodic_order_id');
                    this.updated_at = $(e).data('updated_at');
                    if(this.periodic_interval_type_id === 1){
                        this.interval_days = $(e).data('interval_days');
                        this.auto_shopmemo = this.interval_days + '日間隔';
                        this.old_auto_shopmemo = this.auto_shopmemo;
                        this.old_val = [this.periodic_interval_type_id,this.interval_days].join("-");
                    }else if(this.periodic_interval_type_id === 2){
                        this.interval_month = $(e).data('interval_month');
                        this.interval_date_of_month = $(e).data('interval_date_of_month');
                        this.auto_shopmemo = this.interval_month + 'ヶ月毎'+this.interval_date_of_month+'日';
                        this.old_auto_shopmemo = this.auto_shopmemo;
                        this.old_val = [this.periodic_interval_type_id,this.interval_month,this.interval_date_of_month].join("-");
                    }
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