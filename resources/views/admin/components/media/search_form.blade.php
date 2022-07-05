@inject('mediaCodeGroupList', 'App\Common\KeyValueLists\MediaCodeGroupList')
@inject('itemLineupList', 'App\Common\KeyValueLists\ItemLineupList')
@inject('mediaSummaryGroupList', 'App\Common\KeyValueLists\MediaSummaryGroupList')

{{-- 広告検索フォーム --}}
<div class="card">
    {{-- header start --}}
    <div class="card-header">
        {{__('admin.page_header_name.media_search')}}
        <div class="card-header-actions">
            <label class="switch-sm switch-label switch-outline-primary-alt" role="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="true" aria-controls="search-media-body search-media-footer">
                <input class="switch-input" type="checkbox"  checked="">
                <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
            </label>
        </div>
    </div>

    {{-- body start --}}
    <div id="search-media-body" class="card-body collapse multi-collapse show">
        <div class="row">
            <input id="sort_input" name="sort" type="hidden" value="@if(!is_null(old('sort'))){{old('sort')}}@elseif(isset($search_params['sort'])){{$search_params['sort']}}@endif">
            <input id="page_input" name="page" type="hidden" value="@if(!is_null(old('page'))){{old('page')}}@elseif(isset($search_params['page'])){{$search_params['page']}}@endif">

            {{-- 広告番号 --}}
            <div class="col-6 col-lg-6">
                <div class="row form-group">
                    <label class="col-4 col-lg-2 col-form-label col-form-label-sm" for="text-input">{{__('admin.item_name.media.code')}}</label>
                    <div class="col-8 col-lg-10">
                        <input name="media_code" type="number" min="1" max="2147483647" class="form-control form-control-sm @isInvalid($errors,'media_code')" onchange="app.functions.trim(this); gf_Convert2ByteChar2(this);" onkeydown="app.functions.only_number_key(event);"
                               value="@if(!is_null(old('media_code'))){{old('media_code')}}@elseif(isset($search_params['media_code'])){{$search_params['media_code']}}@endif">
                        <div class="invalid-feedback">{{$errors->first('media_code')}}</div>
                    </div>
                </div>
            </div>

            {{-- 媒体名 --}}
            <div class="col-6 col-lg-6">
                <div class="row form-group">
                    <label class="col-4 col-lg-2 col-form-label col-form-label-sm" for="text-input">{{__('admin.item_name.media.name')}}</label>
                    <div class="col-8 col-lg-10">
                        <input name="media_name" type="text" class="form-control form-control-sm @isInvalid($errors,'media_name')" onchange="return app.functions.trim(this);"
                               value="@if(!is_null(old('media_name'))){{old('media_name')}}@elseif(isset($search_params['media_name'])){{$search_params['media_name']}}@endif">
                        <div class="invalid-feedback">{{$errors->first('media_name')}}</div>
                    </div>
                </div>
            </div>

            {{-- 広告日 --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="col-2 col-lg-1 col-form-label col-form-label-sm" for="text-input">{{__('admin.item_name.media.start_date')}}</label>
                    <div class="col-5 col-lg-5" >
                        <div class="input-group-prepend">
                            <input name="media_date_from" class="form-control form-control-sm @isInvalid($errors,'media_date_from')" type="date" placeholder="" onchange="media_date_from_changed();"
                                   value="@if(!is_null(old('media_date_from'))){{old('media_date_from')}}@elseif(isset($search_params['media_date_from'])){{$search_params['media_date_from']}}@endif">
                            <span class="input-group-text form-control-sm">{{__('admin.item_name.product.date_unit')}}～</span>
                        </div>
                    </div>
                    <div class="col-5 col-lg-6" >
                        <div class="input-group-prepend">
                            <input name="media_date_to" class="form-control form-control-sm @isInvalid($errors,'media_date_to')" type="date" placeholder="" onchange="media_date_to_changed();"
                                   value="@if(!is_null(old('media_date_to'))){{old('media_date_to')}}@elseif(isset($search_params['media_date_to'])){{$search_params['media_date_to']}}@endif">
                            <span class="input-group-text form-control-sm">{{__('admin.item_name.product.date_unit')}}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 媒体別 --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="col-2 col-lg-1 col-form-label col-form-label-sm" for="text-input">{{__('admin.item_name.media.code_group')}}</label>
                    <div class="col-10 col-lg-11 col-form-label col-form-label-sm">
                        @foreach($mediaCodeGroupList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input name="media_code_group[]" class="form-check-input" type="checkbox" id="inlineCheckbox-23-{{$id}}" value="{{$id}}"
                                       @if(!is_null(old('media_code_group')) && in_array($id, old('media_code_group'))) checked @elseif(isset($search_params['media_code_group']) && in_array($id, $search_params['media_code_group'])) checked @endif>
                                <label for="inlineCheckbox-23-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ラインナップ --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="col-2 col-lg-1 col-form-label col-form-label-sm" for="text-input">{{__('admin.item_name.product.lineup')}}</label>
                    <div class="col-10 col-lg-11 col-form-label col-form-label-sm">
                        @foreach($itemLineupList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input name="item_lineup_id[]" class="form-check-input" type="checkbox" id="media-search-form-lineup-{{$id}}" value="{{$id}}"
                                       @if(!is_null(old('item_lineup_id')) && in_array($id, old('item_lineup_id'))) checked @elseif(isset($search_params['item_lineup_id']) && in_array($id, $search_params['item_lineup_id'])) checked @endif>
                                <label for="media-search-form-lineup-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 集計グループ --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="col-2 col-lg-1 col-form-label col-form-label-sm" for="text-input">{{__('admin.item_name.media.summary_group')}}</label>
                    <div class="col-10 col-lg-11 col-form-label col-form-label-sm">
                        @foreach($mediaSummaryGroupList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input name="media_summary_group[]" class="form-check-input" type="checkbox" id="media-search-form-lineup-{{$id}}" value="{{$id}}"
                                       @if(!is_null(old('media_summary_group')) && in_array($id, old('media_summary_group'))) checked @elseif(isset($search_params['media_summary_group']) && in_array($id, $search_params['media_summary_group'])) checked @endif>
                                <label for="media-search-form-lineup-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


        </div>
    </div>

</div>

@push('content_js')
    <script>
        function media_date_from_changed() {
            $('input[name=media_date_to]').attr('min', $('input[name=media_date_from]').val());
        }
        function media_date_to_changed() {
            $('input[name=media_date_from]').attr('max', $('input[name=media_date_to]').val());
        }

        media_date_from_changed();
        media_date_to_changed();

        function gf_Convert2ByteChar2(obj) {
            var x_char = obj.value;
            var x_2byteChar = new String;
            var len = x_char.length;
            for (var i = 0; i < len; i++) {
                var c = x_char.charCodeAt(i);

                if (c >= 65281 && c <= 65374 && c != 65340) {
                    x_2byteChar += String.fromCharCode(c - 65248);
                } else if (c == 8217) {
                    x_2byteChar += String.fromCharCode(39);
                } else if (c == 8221) {
                    x_2byteChar += String.fromCharCode(34);
                } else if (c == 12288) {
                    x_2byteChar += String.fromCharCode(32);
                } else if (c == 65507) {
                    x_2byteChar += String.fromCharCode(126);
                } else if (c == 65509) {
                    x_2byteChar += String.fromCharCode(92);
                } else {
                    x_2byteChar += x_char.charAt(i);
                }
            }
            obj.value = x_2byteChar;
        }
    </script>
@endpush