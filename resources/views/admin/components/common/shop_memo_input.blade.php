@inject('adminNameList', 'App\Common\KeyValueLists\AdminNameList')

{{-- shop memo 入力--}}
<div class="card">
    <div class="card-header">
        <i class="fa fa-info-circle"></i>&nbsp;{{$shopMemoTitle}} @if(isset($shopMemos)){{count($shopMemos)}}@else 0 @endif件
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
                        <textarea class="form-control form-control-sm @isInvalid($errors,'shop_memo_note')" id="" name="shop_memo_note" rows="4" placeholder="" @if($withShopMemoList)required @endif>{{old('shop_memo_note')}}</textarea>
                        <div class="invalid-feedback">{{$errors->first('shop_memo_note')}}</div>
                    </div>

                    <div class="col-12 col-form-label text-sm-right">
                        <div class="form-check form-check-inline mr-1">
                            <input class="form-check-input" id="inline-checkbox1" name="shop_memo_important" type="checkbox" value="1" @if(old('shop_memo_important'))checked @endif>
                            <label class="form-check-label" for="inline-checkbox1">{{__('admin.item_name.shop_memo.important')}}</label>
                        </div>
                        <div class="form-check form-check-inline mr-1">
                            <input class="form-check-input" id="inline-checkbox2" name="shop_memo_claim_flag" type="checkbox" value="1" @if(old('shop_memo_claim_flag'))checked @endif>
                            <label class="form-check-label" for="inline-checkbox2">{{__('admin.item_name.shop_memo.claim_flag')}}</label>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($withShopMemoList)
                {{-- メモ表示領域 --}}
                <div class="col-12" style="position: relative; height: {{$shopMemoHeight}}; overflow: auto; overflow-y: scroll;">
                    {{-- shopメモ1件表示 --}}
                    @if(isset($shopMemos))
                        @foreach($shopMemos as $shopMemo)
                            <div class="row">
                                <div class="col-9">
                                    @if(isset($shopMemo->order_id))
                                        <i class="fa fa-shopping-cart"></i>
                                    @elseif(isset($shopMemo->periodic_order_id))
                                        <i class="fa fa-refresh"></i>
                                    @elseif(!isset($shopMemo->order_id) && !isset($shopMemo->periodic_order_id))
                                        <i class="fa fa-user"></i>
                                    @endif
                                    @datetime($shopMemo->created_at) {{__('admin.item_name.shop_memo.contact_name')}}: {{$adminNameList[$shopMemo->created_by]}}
                                </div>
                                <div class="col-3 text-sm-right">
                                    <i class="fa fa-star @if($shopMemo->important) text-primary @else text-secondary @endif"></i>
                                    <i class="fa fa-warning @if($shopMemo->claim_flag) text-primary @else text-secondary @endif"></i>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-12">
                                    <textarea class="form-control form-control-sm" rows="4" readonly>{{$shopMemo->note}}</textarea>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>