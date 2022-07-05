{{-- modal:カード決済モーダル --}}
<div id="modal_order_credit_form" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__("admin.item_name.order.card_info")}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <p>{{__("admin.help_text.order.card_list")}}</p>
                                <table class="table table-sm table-bordered">
                                    <tr>
                                        <th>{{__('admin.operation.select')}}</th>
                                        <th>{{__('admin.item_name.settlement.card_number')}}</th>
                                        <th>{{__('admin.item_name.settlement.card_valid_term')}}</th>
                                        <th>{{__('admin.item_name.settlement.cardholder_name')}}</th>
                                        <th>{{__("admin.operation.delete")}}</th>
                                    </tr>
                                    <template v-for="settlement in settlements">
                                        <tr>
                                            <td><input type="radio" name="settlement_card_id" :value="settlement.settlement_card_id" :checked="settlement.settlement_card_id==settlement_card_id" required v-model="settlement_card_id"></td>
                                            <td>@{{settlement.card_number}}</td>
                                            <td>@{{settlement.card_valid_term}}</td>
                                            <td>@{{settlement.cardholder_name}}</td>
                                            <td><button type="button" class="btn btn-sm" data-dismiss="modal" @click.prevent="sendTelegramCreditStockDelete"><i class="{{__("admin.icon_class.delete")}}"></i>&nbsp;{{__("admin.operation.delete")}}</button></td>
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
                            {{__("admin.help_text.order.card_list1")}}<br />
                            {{__("admin.help_text.order.card_list2")}}
                        </p>
                    </div>
                </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__("admin.operation.close")}}</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" @click.prevent="paymentUpdate">{{__("admin.operation.confirm1")}}</button>
            </div>
        </div>
    </div>
</div>

@push('content_js')
    <script>
        var modal_order_credit_form = new Vue({
            el:'#modal_order_credit_form',

            data: {
                updated_at:'',
                settlement_card_id: $('#modal_order_credit_form input[name=settlement_card_id]').val(),
                old_settlement_card_id:'',
                settlements:[],
                customer_id:$('input[name=customer_id]').val(),
                errors:{},
                card_info:{},
            },

            methods: {
                showModal: function () {
                    $("#" + this.$el.id).modal("show");
                    this.settlements = [];
                },
                open:function(e){
                    this.showModal();
                    this.getSettlementCards();
                },
                getSettlementCards:function(){
                    this.settlements=[];
                    const form = $("#get_settlement_cards_form")[0];
                    var action = form.action;
                    const dataform = new FormData(form);
                    dataform.append('customer_id',$('input[name=customer_id]').val());
                    axios.post(action, dataform)
                        .then( response => {
                            if(response.data.status=="error"){
                                this.errors = response.data.saved;
                            }else{
                                this.errors = {};
                                this.settlements = response.data.saved;
                                for(var i=0;i<this.settlements.length;i++){
                                    //this.card_info[this.settlements[i].settlement_card_id] = this.settlements[i].card_number;
                                }
                            }
                        })
                        .catch((error) => {
                            this.errors = error.response.data.errors;
                        });
                },
                paymentUpdate: function () {
                    @if($isPeriodic == false)
                        const form = $("#edit_basic_info_form")[0];
                        $('input[name=update_type]').val('paymentUpdate')

                        form.submit();
                    @else
                        this.settlements.forEach(function (settlement) {
                            if (settlement.settlement_card_id == modal_order_credit_form.settlement_card_id) {
                                $('#settlement_card_id_input').val(modal_order_credit_form.settlement_card_id);
                                $('#settlement_masked_card_number_input').val(settlement.card_number);
                                $('#settlement_masked_card_number').val(settlement.card_number);
                            }
                        });
                    @endif
                },
                sendTelegramCreditStockDelete: function () {
                    const form = $("#sendTelegramCreditStockDelete_form")[0];
                    var action = form.action;
                    const dataform = new FormData(form);
                    dataform.append('customer_id',$('input[name=customer_id]').val());
                    dataform.append('settlement_card_id', $('input[name=settlement_card_id]').val());
                    axios.post(action, dataform)
                        .then( response => {
                            if(response.data.status=="warning" || response.data.status=="error"){
                                this.errors = response.data.message;
                            }else{
                                this.errors = {};
                            }
                        })
                        .catch((error) => {
                            this.errors = error.response.data.errors;
                        });
                }
            },
        });
    </script>
@endpush
