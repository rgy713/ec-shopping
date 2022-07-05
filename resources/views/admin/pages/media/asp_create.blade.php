
{{-- ASP一覧 --}}
<div class="col-12">
    <form id="create_form" method="POST" accept-charset="UTF-8" action="{{route('admin.media.asp_create')}}">
        {{csrf_field()}}

        <div class="card">

            <div class="card-header">
                {{__('admin.page_header_name.asp_create')}}
                <div class="card-header-actions">
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    {{-- ASP名 --}}
                    <div class="col-12 col-lg-6">
                        <div class="row form-group">
                            <label class="col-3 col-form-label col-form-label-sm" for="text-input">{{__('admin.item_name.asp.name')}}</label>
                            <div class="col-9">
                                <input name="asp_name" type="text" class="form-control form-control-sm @isInvalid($errors,'asp_name')" onchange="app.functions.trim(this); app.functions.gf_Convert2ByteChar2(this);"
                                       value="@if(!is_null(old('asp_name'))){{ old('asp_name') }}@endif" required>
                                <div class="invalid-feedback">{{$errors->first('asp_name')}}</div>
                            </div>
                        </div>
                    </div>
                    {{-- 付加文字列 --}}
                    <div class="col-12 col-lg-6">
                        <div class="row form-group">
                            <label class="col-3 col-form-label col-form-label-sm" for="text-input">{{__('admin.item_name.asp.remark1')}}</label>
                            <div class="col-9">
                                <input name="asp_remark1" type="text" class="form-control form-control-sm @isInvalid($errors,'asp_remark1')" onchange="return app.functions.trim(this);"
                                       value="@if(!is_null(old('asp_remark1'))){{ old('asp_remark1') }}@endif">
                                <div class="invalid-feedback">{{$errors->first('asp_remark1')}}</div>
                            </div>
                        </div>
                    </div>
                    {{-- 広告費 --}}
                    <div class="col-12 col-lg-6">
                        <div class="row form-group">
                            <label class="col-3 col-form-label col-form-label-sm" for="text-input">{{__("admin.item_name.media.cost")}}</label>
                            <div class="col-9">
                                <input name="asp_default_cost" type="number" min="0"  max="2147483647" class="form-control form-control-sm  @isInvalid($errors,'asp_default_cost')" onchange="return app.functions.trim(this);"  onkeydown="app.functions.only_number_key(event);"
                                       value="@if(!is_null(old('asp_default_cost'))){{ old('asp_default_cost') }}@endif" required>
                                <div class="invalid-feedback">{{$errors->first('asp_default_cost')}}</div>
                            </div>
                        </div>
                    </div>
                    {{-- ラインナップ --}}
                    <div class="col-12 col-lg-6">
                        <div class="row form-group">
                            <label class="col-3 col-form-label col-form-label-sm" for="text-input">{{__("admin.item_name.product.lineup")}}</label>
                            <div class="col-9">
                                <select name="asp_default_item_lineup_id" class="form-control form-control-sm @isInvalid($errors,'asp_default_item_lineup_id')" required>
                                    <option></option>
                                    @foreach($itemLineupList as $id => $name)
                                        <option value="{{$id}}" @if(!is_null(old('asp_default_item_lineup_id')) && (old('asp_default_item_lineup_id') == $id)) selected @endif>{{$name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">{{$errors->first('asp_default_item_lineup_id')}}</div>
                            </div>
                        </div>
                    </div>
                    {{-- 有効無効 --}}
                    <div class="col-12 col-lg-6">
                        <div class="row form-group">
                            <label class="col-3 col-form-label col-form-label-sm @isInvalid($errors,'product_name')" for="text-input">{{__("admin.item_name.common.enabled_disabled")}}</label>
                            <div class="col-9">
                                <select name="asp_enabled" class="form-control form-control-sm @isInvalid($errors,'asp_enabled')" required>
                                    @foreach($enabledDisabledList as $id => $name)
                                        <option value="{{$id}}" @if(!is_null(old('asp_enabled')) && (old('asp_enabled') == $id)) selected @endif>{{$name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">{{$errors->first('asp_enabled')}}</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ボタン表示 start --}}
        <div class="col-12" class="mb-20">
            <div class="row">
                <div class="offset-3 col-6">
                    <button class="btn btn-sm btn-block btn-primary" type="submit">{{__('admin.operation.create1')}}</button>
                </div>
            </div>
        </div>
        {{-- ボタン表示 end --}}
    </form>
</div>