@inject('settlementStatusCodeList', 'App\Common\KeyValueLists\SettlementStatusCodeList')

<div class="card">
    <div class="card-header">
        {{__('admin.page_header_name.order_payment_info')}}
        <div class="card-header-actions">
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    {{-- カード番号 --}}
                    <div class="col-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.settlement_masked_card_number')}}</label>
                            <div class="col-8 col-lg-10">
                                <p class="form-control-plaintext form-control-sm">
                                    @{{settlement_masked_card_number}}
                                </p>
                            </div>
                        </div>
                    </div>
                    {{-- カードステータス --}}
                    <div class="col-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.settlement_sub_status_code')}}</label>
                            <div class="col-8 col-lg-10">
                                <p id="settlement_sub_status_code" class="form-control-plaintext form-control-sm"
                                   data-codes="{{$settlementStatusCodeList}}"
                                   data-value="@if(isset($order) && isset($order['settlement_sub_status_code'])){{$order['settlement_sub_status_code']}}@endif">
                                    @{{ settlement_sub_status_code_label }}
                                </p>
                            </div>
                        </div>
                    </div>
                    {{-- 電文送信 --}}
                    <div v-if="settlement_sub_status_code === '022' || settlement_sub_status_code === '029'" class="col-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.send_post')}}</label>
                            <div class="col-8 col-lg-10">
                                <button type="button" class="btn btn-sm btn-primary" @click.prevent="sendTelegramCreditCommitRevise">{{__('admin.item_name.order.sale_update')}}</button>
                                <button type="button" class="btn btn-sm btn-warning" @click.prevent="sendTelegramCreditCommitCancel">{{__('admin.item_name.order.sale_cancel')}}</button>
                            </div>
                        </div>
                    </div>
                    {{-- エラーコード --}}
                    <div v-if="settlement_sub_response_code" class="col-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.error_code')}}</label>
                            <div class="col-8 col-lg-10">
                                <p id="settlement_sub_response_code" class="form-control-plaintext form-control-sm"
                                   data-value="@if(isset($order) && isset($order['settlement_sub_response_code'])){{$order['settlement_sub_response_code']}}@endif">
                                    @{{  settlement_sub_response_code }}
                                </p>
                            </div>
                        </div>
                    </div>
                    {{-- エラー詳細 --}}
                    <div v-if="settlement_sub_response_detail" class="col-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.error_detail')}}</label>
                            <div class="col-8 col-lg-10">
                                <p id="settlement_sub_response_detail" class="form-control-plaintext form-control-sm"
                                   data-value="@if(isset($order) && isset($order['settlement_sub_response_detail'])){{$order['settlement_sub_response_detail']}}@endif">
                                    @{{ settlement_sub_response_detail }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('content_js')
    <script>
        const order_payment_vue = new Vue({
            el: '#edit_payment_detail_info_form',

            data: {
                settlement_id: $('input[name=settlement_id]').val(),
                settlement_masked_card_number: $("input[name=settlement_masked_card_number]").val(),
                settlement_sub_status_code: $("#settlement_sub_status_code").data('value'),
                settlement_sub_response_code: $("#settlement_sub_response_code").data('value'),
                settlement_sub_response_detail: $("#settlement_sub_response_detail").data('value'),
            },
            computed: {
                settlement_sub_status_code_label: function () {
                    var codes = $("#settlement_sub_status_code").data('codes');
                    return codes[this.settlement_sub_status_code] ? codes[this.settlement_sub_status_code] : "{{(__('admin.item_name.order.no_payment'))}}";
                }
            },
            methods: {
                sendTelegramCreditCommitRevise: function(){

                    const form = $("#sendTelegramCreditCommitRevise_form")[0];
                    var form_action = form.action;
                    const form_data = new FormData(form);
                    form_data.set('order_id', $('input[name=order_id]').val());
                    form_data.set('settlement_id', $('input[name=settlement_id]').val());
                    console.log($('input[name=order_id]').val())
                    axios.post(form_action, form_data)
                        .then( response => {
                            if(response.data.status=="warning" || response.data.status=="error"){
                                this.errors = response.data.message;
                            }else{
                                this.errors = {};
                                var result = response.data.saved;
                                $('input[name=settlement_id]').val(result.settlement_id);
                                order_payment_vue.settlement_sub_status_code = result.settlement_sub_status_code;
                                order_payment_vue.settlement_sub_response_code = result.settlement_sub_response_code;
                                order_payment_vue.settlement_sub_response_detail = result.settlement_sub_response_detail;
                            }
                        })
                        .catch((error) => {
                            this.errors = error.response.data.errors;
                        });
                },
                sendTelegramCreditCommitCancel: function () {
                    const form = $("#sendTelegramCreditCommitCancel_form")[0];
                    var form_action = form.action;
                    const form_data = new FormData(form);
                    form_data.set('order_id', $('input[name=order_id]').val());
                    form_data.set('settlement_id', $('input[name=settlement_id]').val());
                    axios.post(form_action, form_data)
                        .then( response => {
                            if(response.data.status=="warning" || response.data.status=="error"){
                                this.errors = response.data.message;
                            }else{
                                this.errors = {};
                                var result = response.data.saved;
                                $('input[name=settlement_id]').val(result.settlement_id);
                                order_payment_vue.settlement_sub_status_code = result.settlement_sub_status_code;
                                order_payment_vue.settlement_sub_response_code = result.settlement_sub_response_code;
                                order_payment_vue.settlement_sub_response_detail = result.settlement_sub_response_detail;
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