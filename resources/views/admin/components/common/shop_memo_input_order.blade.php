@inject('adminNameList', 'App\Common\KeyValueLists\AdminNameList')

{{-- shop memo 入力--}}
<div class="card">
    <div class="card-header">
        <i class="fa fa-info-circle"></i>&nbsp;{{$shopMemoTitle}} @if($withShopMemoList) @{{shopMemos.length}} @else 0 @endif{{__('admin.item_name.common.unit')}}
        <div class="card-header-actions">
        </div>
    </div>
    <div class="card-body">

        <div class="row">
            @if($withShopMemoInput)
            {{-- メモ入力領域 --}}
            <div class="col-12">
                <div class="row form-group">
                    <div class="col-12">
                        <textarea name="shop_memo_note" @if(isset($info)) readonly @endif class="form-control form-control-sm @isInvalid($errors,'shop_memo_note')" id="" rows="4" placeholder="" @if(isset($mode) && $mode === 'edit') required @endif>@if(!is_null(old('shop_memo_note'))){{old('shop_memo_note')}}@elseif(isset($order) && isset($order['shop_memo_note'])){{$order['shop_memo_note']}}@endif</textarea>
                        <div class="invalid-feedback">{{$errors->first('shop_memo_note')}}</div>
                    </div>

                    <div class="col-12 col-form-label text-sm-right">
                        <div class="form-check form-check-inline mr-1">
                            <input name="shop_memo_important" @if(isset($info)) onclick="return false;" @endif class="form-check-input" id="inline-checkbox1" type="checkbox" value="1"
                                   @if(!is_null(old('shop_memo_important')) && old('shop_memo_important')) checked @elseif(isset($order) && isset($order['shop_memo_important']) && $order['shop_memo_important']) checked @endif>
                            <label class="form-check-label" for="inline-checkbox1">{{__('admin.item_name.shop_memo.important')}}</label>
                        </div>
                        <div class="form-check form-check-inline mr-1">
                            <input name="shop_memo_claim_flag" @if(isset($info)) onclick="return false;" @endif class="form-check-input" id="inline-checkbox2"type="checkbox" value="1"
                                   @if(!is_null(old('shop_memo_claim_flag')) && old('shop_memo_claim_flag')) checked @elseif(isset($order) && isset($order['shop_memo_claim_flag']) && $order['shop_memo_claim_flag']) checked @endif>
                            <label class="form-check-label" for="inline-checkbox2">{{__('admin.item_name.shop_memo.claim_flag')}}</label>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($withShopMemoList)
                {{-- メモ表示領域 --}}
                <div id="shopMemos_{{$prefix}}" class="col-12" style="position: relative; height: {{$shopMemoHeight}}; overflow: auto; overflow-y: scroll;"
                    data-value="@if(isset($shopMemos)){{json_encode($shopMemos)}}@endif"
                    data-admin_names="{{$adminNameList}}">
                    @if($prefix === 'customer')
                        <div class="col-12" v-if="customer_id > 0 ? false: true" >
                            {{__('admin.help_text.order.no_shop_memo_by_customer')}}
                        </div>
                    @endif
                    {{-- shopメモ1件表示 --}}
                    <div v-for="shopMemo in shopMemos">
                        <div class="row">
                            <div class="col-9">
                                <i v-if="shopMemo.order_id > 0" class="fa fa-shopping-cart"></i>
                                <i v-else-if="shopMemo.periodic_order_id > 0" class="fa fa-refresh"></i>
                                <i v-else class="fa fa-user"></i>
                                <span class="local-datetime">@{{ shopMemo.created_at | moment("YYYY-MM-DD HH:mm") }}</span> {{__('admin.item_name.shop_memo.contact_name')}}: @{{ adminNames[shopMemo.created_by] }}
                            </div>
                            <div class="col-3 text-sm-right">
                                <i class="fa fa-star" :class="shopMemo.important ? 'text-primary' : 'text-secondary'"></i>
                                <i class="fa fa-warning" :class="shopMemo.claim_flag ? 'text-primary' : 'text-secondary'"></i>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12">
                                <textarea class="form-control form-control-sm" rows="4" readonly>@{{shopMemo.note}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('content_js')
    <script>
        const shop_memo_vue_{{$prefix}} = new Vue({
            el: '#shop_memo_info_{{$prefix}}',
            data: {
                customer_id: $('input[name=customer_id]').val(),
                shopMemos: $('#shopMemos_{{$prefix}}').data('value'),
                adminNames: $('#shopMemos_{{$prefix}}').data('admin_names'),
            },
        })
        @if($prefix === 'customer')
            $('input[name=customer_id]').change(function () {
                shop_memo_vue_{{$prefix}}.customer_id = this.value;
            })
        @endif
    </script>
@endpush