<div class="card" id="modal_search_form_body">
    <div class="card-body">
        <div class="row">
            <input id="product_sort_input" name="sort" type="hidden" form="product_search_form" >
            <input id="product_number_per_page" name="number_per_page" type="hidden" form="product_search_form" value="10">

            {{-- 商品コード --}}
            <div class="col-6">
                <div class="row form-group">
                    <label class="col-4 col-form-label form-control-sm">{{__('admin.item_name.product.code')}}</label>

                    <div class="col-8 input-group-sm">
                        <input name="product_code" type="text" form="product_search_form" class="form-control form-control-sm search_form_input" placeholder="{{__('admin.item_name.common.whole_search')}}" onchange="return app.functions.trim(this);"
                               @blur="search_results()">
                    </div>
                </div>
            </div>

            {{-- 商品名 --}}
            <div class="col-6">
                <div class="row form-group">
                    <label class="col-4 col-form-label form-control-sm">{{__('admin.item_name.product.name')}}</label>

                    <div class="col-8 input-group-sm">
                        <input name="product_name" type="text" maxlength="50" form="product_search_form" class="form-control form-control-sm search_form_input" placeholder="{{__('admin.item_name.common.partial_search')}}"  onchange="return app.functions.trim(this);"
                               @blur="search_results()">
                    </div>
                </div>
            </div>

            {{-- ラインナップ --}}
            <div class="col-12">
                <div class="row form-group">
                    <label class="col-2 col-form-label form-control-sm">{{__('admin.item_name.product.lineup')}}</label>
                    <div class="col-10 col-form-label col-form-label-sm">
                        @foreach($itemLineupList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input name="item_lineup_id[]" type="checkbox" form="product_search_form" class="form-check-input search_form_input" id="modal_product_lineup-{{$id}}" value="{{$id}}"
                                       @change="search_results()">
                                <label for="modal_product_lineup-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- マーケティング集計区分 --}}
            <div class="col-12">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label form-control-sm">{{__('admin.item_name.product.marketing_summary_classification')}}</label>
                    <div class="col-sm-10 col-form-label col-form-label-sm">
                        @foreach($marketingSummaryClassificationList as $id=>$name)
                            <div class="form-check form-check-inline">
                                <input name="marketing_summary_classification_id[]" form="product_search_form" type="checkbox" class="form-check-input search_form_input" id="modal_product_marketing-classification-{{$id}}" value="{{$id}}"
                                       @change="search_results()">
                                <label for="modal_product_marketing-classification-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>