@inject('marketingTagVendorList', 'App\Common\KeyValueLists\MarketingTagVendorList')
@inject('enabledDisabledList', 'App\Common\KeyValueLists\EnabledDisabledList')
@inject('mediaTagPositionList', 'App\Common\KeyValueLists\MediaTagPositionList')
@inject('browsingDeviceTypeList', 'App\Common\KeyValueLists\BrowsingDeviceTypeList')

{{-- タグ編集 --}}
<div class="card">
    <div class="card-header">
        タグ情報
        <div class="card-header-actions">
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            {{-- 名称 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-2 col-lg-4 col-form-label col-form-label-sm" for="text-input">{{__("admin.item_name.tag.name")}}</label>
                    <div class="col-8">
                        <input type="text" class="form-control form-control-sm" value="" placeholder="">
                        <small class="form-text text-muted">{{__("admin.help_text.tag.name")}}</small>
                    </div>
                </div>
            </div>

            {{-- タグのベンダー --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-2 col-lg-4 col-form-label col-form-label-sm" for="text-input">{{__("admin.item_name.tag.vendor")}}</label>
                    <div class="col-8">
                        <input class="form-control form-control-sm" name="" type="text" placeholder="" list="tag-vendors">
                        <small class="form-text text-muted">{{__("admin.help_text.tag.vendor")}}</small>

                        <datalist id="tag-vendors">
                            @foreach($marketingTagVendorList as $item)
                                <option value="{{$item}}"></option>
                            @endforeach
                        </datalist>

                    </div>
                </div>
            </div>

            {{-- 有効 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-2 col-lg-4 col-form-label col-form-label-sm">{{__("admin.item_name.tag.enabled")}}</label>
                    <div class="col-10 col-lg-8">
                        <select class="form-control form-control-sm" name="">
                            @foreach($enabledDisabledList as $id => $name)
                                <option value="{{$id}}">{{$name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 設置位置 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-2 col-lg-4 col-form-label col-form-label-sm">{{__("admin.item_name.tag.position")}}</label>
                    <div class="col-10 col-lg-8">

                        <select class="form-control form-control-sm" name="">
                            @foreach($mediaTagPositionList as $id => $name)
                                <option value="{{$id}}">{{$name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 優先度 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-2 col-lg-4 col-form-label col-form-label-sm">{{__("admin.item_name.common.rank")}}</label>
                    <div class="col-10 col-lg-8">
                        <input type="number" class="form-control form-control-sm" value="xxx" min="0">
                        <small class="form-text text-muted">{{__("admin.help_text.tag.rank")}}</small>
                    </div>
                </div>
            </div>

            {{-- 備考 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-2 col-lg-4 col-form-label col-form-label-sm">{{__("admin.item_name.common.remark")}}</label>
                    <div class="col-10 col-lg-8">
                        <textarea class="form-control form-control-sm" id="textarea-input" name="textarea-input" rows="2" placeholder="管理者向けメモ"></textarea>
                        <small class="form-text text-muted">{{__("admin.help_text.common.for_admin")}}</small>
                    </div>
                </div>
            </div>

            {{-- 設置内容 --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="col-2 col-form-label col-form-label-sm">{{__("admin.item_name.tag.content")}}</label>
                    <div class="col-10">
                        <textarea class="form-control form-control-sm" name="" rows="9" placeholder="Content.."></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>