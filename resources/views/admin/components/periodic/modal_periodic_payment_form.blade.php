{{-- modal5:支払い方法変更 --}}
<div id="modal_periodic_payment_form" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('admin.item_name.order.payment_method')}}の変更</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.customer.periodic.payment.update')}}" method="POST" accept-charset="UTF-8" @submit.prevent="onSubmit">
                {{ csrf_field() }}
                <input type="hidden" name="periodic_order_id" v-model="periodic_order_id">
                <input type="hidden" name="updated_at" v-model="updated_at">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            {{-- 対応状況 --}}
                            <div class="row form-group">
                                <label class="col-3 col-form-label form-control-sm">{{__('admin.item_name.order.payment_method')}}</label>

                                <div class="col-9 input-group-sm">
                                    <select class="form-control form-control-sm" name="payment_method_id" v-model="payment_method_id" v-on:change="changeSelect">
                                        @foreach($paymentList as $id=> $name)
                                            <option value="{{$id}}">{{$name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            {{-- shopmemo --}}
                            <div class="row mb-sm-3">
                                <label class="col-3 col-form-label form-control-sm">{{__("admin.item_name.common.shop_memo")}}</label>
                                <div class="col-9">
                                    <textarea class="form-control form-control-sm" name="shop_memo_auto" rows="1" placeholder="" readonly>支払方法を@{{auto_shopmemo}}</textarea>
                                    <textarea class="form-control form-control-sm" name="shop_memo_body" rows="3" placeholder=""></textarea>
                                </div>
                            </div>

                            <div class="row mb-sm-3" v-if="payment_method_id==5">
                                <div class="col-12 col-sm">
                                    <p class="text-sm-left">決済に利用するクレジットカードを選択してください。</p>
                                    <table class="table table-sm table-bordered">
                                        <tr>
                                            <th>{{__('admin.operation.select')}}</th>
                                            <th>{{__('admin.item_name.settlement.card_number')}}</th>
                                            <th>{{__('admin.item_name.settlement.card_valid_term')}}</th>
                                            <th>{{__('admin.item_name.settlement.cardholder_name')}}</th>
                                        </tr>
                                        <template v-for="settlement in settlements">
                                            <tr>
                                                <td><input type="radio" name="settlement_card_id" :value="settlement.settlement_card_id" :checked="settlement.settlement_card_id==settlement_card_id" required v-on:click="changeCard(settlement.settlement_card_id)"></td>
                                                <td>@{{settlement.card_number}}</td>
                                                <td>@{{settlement.card_valid_term}}</td>
                                                <td>@{{settlement.cardholder_name}}</td>
                                            </tr>
                                        </template>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12">
                            <p>
                                以上の内容で間違いなければ、下記「送信」ボタンをクリックしてください。<br />
                                ※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。
                            </p>
                        </div>
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('admin.operation.close')}}</button>
                    <button type="submit" class="btn btn-primary">{{__('admin.operation.save')}}</button>
                </div>
            </form>
            <form id="get_settlement_cards_form" action="{{route('admin.customer.periodic.payment.settlements')}}" method="POST" accept-charset="UTF-8">
                {{ csrf_field() }}
            </form>
        </div>
    </div>
</div>

@push('content_js')
    <script>
        var modal_periodic_payment_form = new Vue({
            el:'#modal_periodic_payment_form',
            data:function(){
                return{
                    payment_method_id:'',
                    old_payment_method_id:'',
                    payment_list:{},
                    stop_reason:[],
                    updated_at:'',
                    periodic_order_id:'',
                    settlement_card_id:'',
                    old_settlement_card_id:'',
                    settlements:[],
                    customer_id:'',
                    auto_shopmemo:'',
                    errors:{},
                    card_info:{},
                }
            },
            methods:{
                showModal:function(){
                    $("#"+this.$el.id).modal("show");
                    this.settlements=[];
                },
                changeSelect:function(){
                    if(this.payment_method_id != this.old_payment_method_id)
                        this.auto_shopmemo = this.payment_list[this.old_payment_method_id] + "から" + this.payment_list[this.payment_method_id] + "へ変更";
                    else
                        this.auto_shopmemo = this.payment_list[this.payment_method_id];

                    if(this.payment_method_id==5){
                        this.getSettlementCards();
                    }
                },
                changeCard: function(card_id){
                    if(this.old_settlement_card_id != card_id)
                        this.auto_shopmemo = this.payment_list[this.old_payment_method_id] + this.card_info[this.old_settlement_card_id] + "から" + this.payment_list[this.payment_method_id] + this.card_info[card_id] + "へ変更";
                    else
                        this.auto_shopmemo = this.payment_list[this.old_payment_method_id] + this.card_info[this.old_settlement_card_id]
                },
                open:function(e){
                    this.payment_method_id = $(e).data('payment_method_id');
                    this.settlement_card_id = $(e).data('settlement_card_id');
                    this.old_settlement_card_id = this.settlement_card_id;
                    this.customer_id = $(e).data('customer_id');
                    this.old_payment_method_id = this.payment_method_id;
                    this.periodic_order_id = $(e).data('periodic_order_id');
                    this.updated_at = $(e).data('updated_at');
                    this.payment_list = $(e).data('payment_list');
                    this.auto_shopmemo = this.payment_list[this.payment_method_id];
                    this.showModal();
                    if(this.payment_method_id==5){
                        this.getSettlementCards();
                    }
                },
                getSettlementCards:function(){
                    //   TODO 電文027送信処理
                    this.settlements=[];
                    const form = $("#get_settlement_cards_form")[0];
                    var action = form.action;
                    const dataform = new FormData(form);
                    dataform.append('customer_id',this.customer_id);
                    axios.post(action, dataform)
                        .then( response => {
                            if(response.data.status=="error"){
                                this.errors = response.data.saved;
                            }else{
                                this.errors = {};
                                this.settlements = response.data.saved;
                                for(var i=0;i<this.settlements.length;i++){
                                    this.card_info[this.settlements[i].settlement_card_id] = this.settlements[i].card_number;
                                }
                                if(this.old_payment_method_id==5)
                                    this.auto_shopmemo = this.payment_list[this.old_payment_method_id] + this.card_info[this.old_settlement_card_id] ;
                            }
                        })
                        .catch((error) => {
                            this.errors = error.response.data.errors;
                        });
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