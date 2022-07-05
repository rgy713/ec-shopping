{{-- 顧客情報：簡易検索 --}}
<div class="card">
    <div class="card-header">
        {{__('admin.page_header_name.customer_search')}}
        <div class="card-header-actions">
            <label class="switch-sm switch-label switch-outline-primary-alt" role="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="true" aria-controls="search-form-body search-form-footer">
                <input class="switch-input" type="checkbox"  checked="">
                <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
            </label>
        </div>
    </div>

    <div id="search-form-body" class="card-body collapse multi-collapse show">
        <div class="row">
            <input id="sort_input" name="sort" type="hidden" value="@if(isset($search_params)){{$search_params['sort']}}@endif">
            <input id="page_input" name="page" type="hidden" value="@if(isset($search_params)){{$search_params['page']}}@endif">

            {{-- 顧客名 --}}
            <div class="col-12 col-md-6">
                <div class="row form-group">
                    <label class="col-sm-3 col-form-label col-form-label-sm">{{__("common.item_name.address.name")}}</label>
                    <div class="col-sm-9">
                        <input class="form-control form-control-sm" name="customer_name" type="text" placeholder="お名前(部分一致)" value="@if(isset($search_params)){{$search_params['customer_name']}}@endif" onchange="return app.functions.trim(this);">
                    </div>
                </div>
            </div>

            {{-- フリガナ --}}
            <div class="col-12 col-md-6">
                <div class="row form-group">
                    <label class="col-sm-3 col-form-label col-form-label-sm" for="text-input">{{__("common.item_name.address.kana")}}</label>
                    <div class="col-sm-9">
                        <input class="form-control form-control-sm" name="customer_kana" type="text" placeholder="フリガナ(部分一致)" value="@if(isset($search_params)){{$search_params['customer_kana']}}@endif" onchange="return app.functions.trim(this);">
                    </div>
                </div>
            </div>

            {{-- email --}}
            <div class="col-12 col-md-6">
                <div class="row form-group">
                    <label class="col-sm-3 col-form-label col-form-label-sm" for="text-input">{{__("admin.item_name.customer.email")}}</label>
                    <div class="col-sm-9">
                        <input class="form-control form-control-sm" type="text" name="customer_email" placeholder="email@example.com (完全一致)" value="@if(isset($search_params)){{$search_params['customer_email']}}@endif" onchange="return app.functions.trim(this);">
                    </div>
                </div>
            </div>

            {{-- 電話番号 --}}
            <div class="col-12 col-md-6">
                <div class="row form-group">
                    <label class="col-sm-3 col-form-label col-form-label-sm" for="text-input">{{__("common.item_name.address.tel")}}</label>
                    <div class="col-sm-9">
                        <input class="form-control form-control-sm" type="text" name="customer_phone" placeholder="03123456789(完全一致)" value="@if(isset($search_params)){{$search_params['customer_phone']}}@endif" onchange="return app.functions.trim(this);">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>