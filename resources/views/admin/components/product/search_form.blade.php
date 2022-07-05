@inject('itemLineupList', 'App\Common\KeyValueLists\ItemLineupList')
@inject('SalesTargetList', 'App\Common\KeyValueLists\SalesTargetList')
@inject('salesRouteList', 'App\Common\KeyValueLists\SalesRouteList')
@inject('productTypeList', 'App\Common\KeyValueLists\ProductTypeList')
@inject('productVolumeList', 'App\Common\KeyValueLists\ProductVolumeList')
@inject('productVisibleList', 'App\Common\KeyValueLists\ProductVisibleList')
@inject('searchResultNumList', 'App\Common\KeyValueLists\SearchResultNumList')
@inject('undeliveredSummaryClassificationList', 'App\Common\KeyValueLists\UndeliveredSummaryClassificationList')
@inject('marketingSummaryClassificationList', 'App\Common\KeyValueLists\MarketingSummaryClassificationList')

{{-- 商品検索フォーム --}}
<div class="card">
    {{-- header start --}}
    <div class="card-header">
        {{__('admin.page_header_name.product_search')}}
        <div class="card-header-actions">
            <label class="switch-sm switch-label switch-outline-primary-alt" role="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="true" aria-controls="search-product-body search-product-footer">
                <input class="switch-input" type="checkbox"  checked="">
                <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
            </label>
        </div>
    </div>
    {{-- header end --}}

    {{-- body start --}}
    <div id="search-product-body" class="card-body collapse multi-collapse show">
        <div class="row">
            <input id="sort_input" name="sort" type="hidden" value="@if(!is_null(old('sort'))){{old('sort')}}@elseif(isset($search_params['sort'])){{$search_params['sort']}}@endif">
            <input id="page_input" name="page" type="hidden" value="@if(!is_null(old('page'))){{old('page')}}@elseif(isset($search_params['page'])){{$search_params['page']}}@endif">

            {{-- 商品ID --}}
            <div class="col-6 col-lg-6">
                <div class="row form-group">
                    <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.product.id')}}</label>
                    <div class="col-8 col-lg-10">
                        <input name="product_id" type="number" min="1" max="32767" class="form-control form-control-sm @isInvalid($errors,'product_id')" onchange="return app.functions.trim(this);"  onkeydown="app.functions.only_number_key(event);"
                               value="@if(!is_null(old('product_id'))){{old('product_id')}}@elseif(isset($search_params['product_id'])){{$search_params['product_id']}}@endif">
                        <div class="invalid-feedback">{{$errors->first('product_id')}}</div>
                    </div>
                </div>
            </div>

            {{-- 商品コード --}}
            <div class="col-6 col-lg-6">
                <div class="row form-group">
                    <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.product.code')}}</label>
                    <div class="col-8 col-lg-10">
                        <input name="product_code" type="text" class="form-control form-control-sm @isInvalid($errors,'product_code')" onchange="return app.functions.trim(this);"
                               value="@if(!is_null(old('product_code'))){{old('product_code')}}@elseif(isset($search_params['product_code'])){{$search_params['product_code']}}@endif">
                        <div class="invalid-feedback">{{$errors->first('product_code')}}</div>
                    </div>
                </div>
            </div>

            {{-- 商品名 --}}
            <div class="col-6 col-lg-6">
                <div class="row form-group">
                    <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.product.name')}}</label>
                    <div class="col-8 col-lg-10">
                        <input name="product_name" type="text" maxlength="50" class="form-control form-control-sm @isInvalid($errors,'product_name')" onchange="return app.functions.trim(this);"
                               value="@if(!is_null(old('product_name'))){{old('product_name')}}@elseif(isset($search_params['product_name'])){{$search_params['product_name']}}@endif">
                        <div class="invalid-feedback">{{$errors->first('product_name')}}</div>
                    </div>
                </div>
            </div>

            {{-- 公開/非公開 --}}
            <div class="col-6 col-lg-6">
                <div class="row form-group">
                    <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.product.visible')}}</label>

                    <div class="col-8 col-lg-10  col-form-label col-form-label-sm">
                        @foreach($productVisibleList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input name="user_visible[]" class="form-check-input" type="checkbox" id="inlineCheckbox-21-{{$id}}" value="{{$id}}"
                                       @if(!is_null(old('user_visible')) && in_array($id, old('user_visible'))) checked @elseif(isset($search_params['user_visible']) && in_array($id, $search_params['user_visible'])) checked @endif>
                                <label for="inlineCheckbox-21-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 商品ラインナップ --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="col-2 col-lg-1 col-form-label col-form-label-sm">{{__('admin.item_name.product.lineup')}}</label>
                    <div class="col-10 col-lg-11 col-form-label col-form-label-sm">
                        @foreach($itemLineupList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input name="item_lineup_id[]" class="form-check-input" type="checkbox" id="inlineCheckbox-20-{{$id}}" value="{{$id}}"
                                       @if(!is_null(old('item_lineup_id')) && in_array($id, old('item_lineup_id'))) checked @elseif(isset($search_params['item_lineup_id']) && in_array($id, $search_params['item_lineup_id'])) checked @endif>
                                <label for="inlineCheckbox-20-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


            {{-- 分類/顧客 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__('admin.item_name.product.target_customer')}}</label>
                    <div class="col-sm-10 col-form-label col-form-label col-form-label-sm">
                        @foreach($SalesTargetList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input name="sales_target_id[]" class="form-check-input" type="checkbox" id="inlineCheckbox-21-{{$id}}" value="{{$id}}"
                                       @if(!is_null(old('sales_target_id')) && in_array($id, old('sales_target_id'))) checked @elseif(isset($search_params['sales_target_id']) && in_array($id, $search_params['sales_target_id'])) checked @endif>
                                <label for="inlineCheckbox-21-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 分類/経路 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__('admin.item_name.product.sales_route')}}</label>
                    <div class="col-sm-10 col-form-label col-form-label col-form-label-sm">
                        @foreach($salesRouteList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input  name="sales_route_id[]" class="form-check-input" type="checkbox" id="inlineCheckbox-22-{{$id}}" value="{{$id}}"
                                       @if(!is_null(old('sales_route_id')) && in_array($id, old('sales_route_id'))) checked @elseif(isset($search_params['sales_route_id']) && in_array($id, $search_params['sales_route_id'])) checked @endif>
                                <label for="inlineCheckbox-22-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 未発送集計区分 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__('admin.item_name.product.undelivered_summary_classification')}}</label>
                    <div class="col-sm-10 col-form-label col-form-label col-form-label-sm">
                        @foreach($undeliveredSummaryClassificationList as $id=>$name)
                            <div class="form-check form-check-inline">
                                <input name="undelivered_summary_classification_id[]" class="form-check-input" type="checkbox" id="inlineCheckbox-23-{{$id}}" value="{{$id}}"
                                       @if(!is_null(old('undelivered_summary_classification_id')) && in_array($id, old('undelivered_summary_classification_id'))) checked @elseif(isset($search_params['undelivered_summary_classification_id']) && in_array($id, $search_params['undelivered_summary_classification_id'])) checked @endif>
                                <label for="inlineCheckbox-23-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- マーケティング集計区分 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__('admin.item_name.product.marketing_summary_classification')}}</label>
                    <div class="col-sm-10 col-form-label col-form-label col-form-label-sm">
                        @foreach($marketingSummaryClassificationList as $id=>$name)
                            <div class="form-check form-check-inline">
                                <input name="marketing_summary_classification_id[]" class="form-check-input" type="checkbox" id="inlineCheckbox-23-{{$id}}" value="{{$id}}"
                                       @if(!is_null(old('marketing_summary_classification_id')) && in_array($id, old('marketing_summary_classification_id'))) checked @elseif(isset($search_params['marketing_summary_classification_id']) && in_array($id, $search_params['marketing_summary_classification_id'])) checked @endif>
                                <label for="inlineCheckbox-23-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 分類/本数 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__('admin.item_name.product.volume')}}</label>
                    <div class="col-sm-10 col-form-label col-form-label col-form-label-sm">
                        @foreach($productVolumeList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input name="product_volume[]" class="form-check-input" type="checkbox" id="inlineCheckbox-23-{{$id}}" value="{{$id}}"
                                       @if(!is_null(old('product_volume')) && in_array($id, old('product_volume'))) checked @elseif(isset($search_params['product_volume']) && in_array($id, $search_params['product_volume'])) checked @endif>
                                <label for="inlineCheckbox-23-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- body end --}}

</div>