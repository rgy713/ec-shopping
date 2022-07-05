@inject('mediaTypeList', 'App\Common\KeyValueLists\MediaTypeList')
@inject('mediaAreaList', 'App\Common\KeyValueLists\MediaAreaList')
@inject('mediaNameList', 'App\Common\KeyValueLists\MediaNameList')
@inject('itemLineupList', 'App\Common\KeyValueLists\ItemLineupList')
@inject('mediaSummaryGroupList', 'App\Common\KeyValueLists\MediaSummaryGroupList')


{{-- 広告媒体編集 --}}
<div class="card">
    <div class="card-header">
        {{__('admin.page_header_name.media_create')}}
        <div class="card-header-actions">
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            {{-- 広告番号 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.code')}}</label>
                    <div class="col-9">
                        <input name="media_code" type="number" min="1" max="2147483647" class="form-control form-control-sm @isInvalid($errors,'media_code')" onchange="app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);"
                               value="@if(!is_null(old('media_code'))){{ old('media_code') }}@elseif(isset($media)){{$media->code}}@endif" required @if(isset($media) && isset($media->code)) readonly @endif>
                        <div class="invalid-feedback">{{$errors->first('media_code')}}</div>
                    </div>
                </div>
            </div>

            {{-- 媒体種別 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.type')}}</label>
                    <div class="col-9">
                        <select name="media_type_id" id="media_type_id" class="form-control form-control-sm @isInvalid($errors,'media_type_id')" required>
                            @foreach($mediaTypeList as $id => $name)
                                <option value="{{$id}}" @if(!is_null(old('media_type_id')) && old('media_type_id') == $id) selected @elseif(isset($media) && $media->media_type_id == $id) selected @endif>{{$name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{$errors->first('media_type_id')}}</div>
                    </div>
                </div>
            </div>

            {{-- 媒体エリア --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.area')}}</label>
                    <div class="col-9">
                        <input name="media_area" type="text" class="form-control form-control-sm @isInvalid($errors,'media_area')" onchange="app.functions.trim(this);"
                               value="@if(!is_null(old('media_area'))){{ old('media_area') }}@elseif(isset($media)){{$media->area}}@endif">
                        <div class="invalid-feedback">{{$errors->first('media_area')}}</div>
                    </div>
                </div>
            </div>

            {{-- 放送局 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.broadcaster')}}</label>
                    <div class="col-9">
                        <input name="media_broadcaster" type="text" class="form-control form-control-sm @isInvalid($errors,'media_broadcaster')" onchange="app.functions.trim(this);"
                               value="@if(!is_null(old('media_broadcaster'))){{ old('media_broadcaster') }}@elseif(isset($media)){{$media->broadcaster}}@endif">
                        <div class="invalid-feedback">{{$errors->first('media_broadcaster')}}</div>
                    </div>
                </div>
            </div>

            {{-- 媒体名 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.name')}}</label>
                    <div class="col-9">
                        <input name="media_name" type="text" class="form-control form-control-sm @isInvalid($errors,'media_name')" onchange="app.functions.trim(this);"
                               value="@if(!is_null(old('media_name'))){{ old('media_name') }}@elseif(isset($media)){{$media->name}}@endif" required>
                        <div class="invalid-feedback">{{$errors->first('media_name')}}</div>
                    </div>
                </div>
            </div>

            {{-- 媒体詳細 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.detail')}}</label>
                    <div class="col-9">
                        <input name="media_detail" type="text" class="form-control form-control-sm @isInvalid($errors,'media_detail')" placeholder="{{__('admin.help_text.media.detail')}}" onchange="app.functions.trim(this);"
                               value="@if(!is_null(old('media_detail'))){{ old('media_detail') }}@elseif(isset($media)){{$media->detail}}@endif">
                        <div class="invalid-feedback">{{$errors->first('media_detail')}}</div>
                    </div>
                </div>
            </div>

            {{-- 金額 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.cost')}}</label>

                    <div class="col-9">
                        <div class="input-group-prepend">
                            <input name="media_cost" type="number" min="0" max="2147483647"  class="form-control form-control-sm @isInvalid($errors,'media_cost')" onchange="app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);"
                                   value="@if(!is_null(old('media_cost'))){{ old('media_cost') }}@elseif(isset($media)){{$media->cost}}@endif" required>
                            <span class="input-group-text form-control-sm">{{__('admin.item_name.common.wen')}}</span>
                            <div class="invalid-feedback">{{$errors->first('media_cost')}}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 広告日 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.start_date')}}</label>

                    <div class="col-9">
                        <div class="input-group-prepend">
                            <input name="media_date" type="date" class="form-control form-control-sm @isInvalid($errors,'media_date')"
                                   value="@if(!is_null(old('media_date'))){{ old('media_date') }}@elseif(isset($media)){{$media->date}}@endif" required>
                            <span class="input-group-text form-control-sm">{{__('admin.item_name.common.date_unit')}}</span>
                            <div class="invalid-feedback">{{$errors->first('media_date')}}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 広告開始時刻 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.start_time')}}</label>

                    <div class="col-9">
                        <input name="media_start_time" class="form-control form-control-sm @isInvalid($errors,'media_start_time')" type="text" placeholder="{{__('admin.help_text.media.start_time')}}" list="media-start-time-list"
                               onchange="app.functions.trim(this); gf_Convert2ByteChar2(this);"
                               value="@if(!is_null(old('media_start_time'))){{ old('media_start_time') }}@elseif(isset($media)){{$media->start_time}}@endif">
                        <datalist id="media-start-time-list">
                            <option value="{{__('admin.item_name.media.morning')}}">{{__('admin.item_name.media.morning')}}</option>
                            <option value="{{__('admin.item_name.media.evening')}}">{{__('admin.item_name.media.evening')}}</option>
                        </datalist>
                        <div class="invalid-feedback">{{$errors->first('media_start_time')}}</div>
                    </div>
                </div>
            </div>

            {{-- 放送時間分数 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.broadcast_minutes')}}</label>

                    <div class="col-9">
                        <div class="input-group-prepend">
                            <input name="media_broadcast_minutes" type="number" min="1" max="32767" class="form-control form-control-sm @isInvalid($errors,'media_broadcast_minutes')" onchange="app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);"
                                   value="@if(!is_null(old('media_broadcast_minutes'))){{ old('media_broadcast_minutes') }}@elseif(isset($media)){{$media->broadcast_minutes}}@endif">
                            <span class="input-group-text form-control-sm">{{__('admin.item_name.common.minute')}}</span>
                            <div class="invalid-feedback">{{$errors->first('media_broadcast_minutes')}}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 放送時間枠 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.broadcast_duration')}}</label>

                    <div class="col-5 pr-0">
                        <div class="input-group-prepend">
                            <input name="media_broadcast_duration_from" type="time" class="form-control form-control-sm @isInvalid($errors,'media_broadcast_duration_from')" onchange="media_broadcast_duration_from_changed();"
                                   value="@if(!is_null(old('media_broadcast_duration_from'))){{ old('media_broadcast_duration_from') }}@elseif(isset($media)){{$media->broadcast_duration_from}}@endif">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>

                    </div>
                    <div class="col-4 pl-0">
                        <div class="input-group-prepend">
                            <input name="media_broadcast_duration_to" type="time" class="form-control form-control-sm @isInvalid($errors,'media_broadcast_duration_to')" onchange="media_broadcast_duration_to_changed();"
                                   value="@if(!is_null(old('media_broadcast_duration_to'))){{ old('media_broadcast_duration_to') }}@elseif(isset($media)){{$media->broadcast_duration_to}}@endif">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ラインナップ --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.product.lineup')}}</label>

                    <div class="col-9">
                        <select name="item_lineup_id" class="form-control form-control-sm @isInvalid($errors,'item_lineup_id')" required>
                            <option></option>
                            @foreach($itemLineupList as $id => $name)
                                <option value="{{$id}}" @if(!is_null(old('item_lineup_id')) && old('item_lineup_id') == $id) selected @elseif(isset($media) && $media->item_lineup_id == $id)selected @endif>{{$name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{$errors->first('item_lineup_id')}}</div>
                    </div>
                </div>
            </div>

            {{-- 広告集計グループ --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.summary_group')}}</label>

                    <div class="col-9">
                        <select name="media_summary_group_id" class="form-control form-control-sm @isInvalid($errors,'media_summary_group_id')">
                            <option></option>
                            @foreach($mediaSummaryGroupList as $id => $name)
                                <option value="{{$id}}" @if(!is_null(old('media_summary_group_id')) && old('media_summary_group_id') == $id) selected @elseif(isset($media_summary_group_id) && $media_summary_group_id == $id)selected @endif>{{$name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{$errors->first('media_summary_group_id')}}</div>
                    </div>
                </div>
            </div>

            {{-- 部数 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.circulation')}}</label>

                    <div class="col-9">
                        <div class="input-group-prepend">
                            <input name="media_circulation" type="number" min="1" max="2147483647" class="form-control form-control-sm @isInvalid($errors,'media_circulation')" onchange="app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);"
                                   value="@if(!is_null(old('media_circulation'))){{ old('media_circulation') }}@elseif(isset($media)){{$media->circulation}}@endif">
                            <span class="input-group-text form-control-sm">{{__('admin.item_name.common.unit')}}</span>
                            <div class="invalid-feedback">{{$errors->first('media_circulation')}}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- コール予測数 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.call_expected')}}</label>

                    <div class="col-9">
                        <div class="input-group-prepend">
                            <input name="media_call_expected" type="number" min="0" max="32767" class="form-control form-control-sm @isInvalid($errors,'media_call_expected')" onchange="app.functions.trim(this);" onkeydown="app.functions.only_number_key(event);"
                                   value="@if(!is_null(old('media_call_expected'))){{ old('media_call_expected') }}@elseif(isset($media)){{$media->call_expected}}@endif">
                            <span class="input-group-text form-control-sm">{{__('admin.item_name.common.unit')}}</span>
                            <div class="invalid-feedback">{{$errors->first('media_call_expected')}}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 備考 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.common.remark')}}</label>

                    <div class="col-9">
                        <textarea name="media_remark" class="form-control form-control-sm @isInvalid($errors,'media_remark')" rows="3" onchange="app.functions.trim(this);">@if(!is_null(old('media_remark'))){{ old('media_remark') }}@elseif(isset($media)){{$media->remark}}@endif</textarea>
                        <div class="invalid-feedback">{{$errors->first('media_remark')}}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('content_js')
    <script>
        function media_broadcast_duration_from_changed() {
            $('input[name=media_broadcast_duration_to]').attr('min', $('input[name=media_broadcast_duration_from]').val());
        }
        function media_broadcast_duration_to_changed() {
            $('input[name=media_broadcast_duration_from]').attr('max', $('input[name=media_broadcast_duration_to]').val());
        }

        media_broadcast_duration_from_changed();
        media_broadcast_duration_to_changed();


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

