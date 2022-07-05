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
                        <input name="media_code" class="form-control form-control-sm form-control-plaintext" value="@if(isset($media->code)){{$media->code}}@endif">
                    </div>
                </div>
            </div>

            {{-- 媒体種別 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.type')}}</label>
                    <div class="col-9">
                        <input name="media_type_id" class="form-control form-control-sm form-control-plaintext" value="@if(isset($media->media_type_id)){{$mediaTypeList[$media->media_type_id]}}@endif">
                    </div>
                </div>
            </div>

            {{-- 媒体エリア --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.area')}}</label>
                    <div class="col-9">
                        <input name="media_area" class="form-control form-control-sm form-control-plaintext" value="@if(isset($media->area)){{$media->area}}@endif">
                    </div>
                </div>
            </div>

            {{-- 放送局 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.broadcaster')}}</label>
                    <div class="col-9">
                        <input name="media_broadcaster" class="form-control form-control-sm form-control-plaintext" value="@if(isset($media->broadcaster)){{$media->broadcaster}}@endif">
                    </div>
                </div>
            </div>

            {{-- 媒体名 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.name')}}</label>
                    <div class="col-9">
                        <input name="media_name" class="form-control form-control-sm form-control-plaintext" value="@if(isset($media->name)){{$media->name}}@endif">
                    </div>
                </div>
            </div>

            {{-- 媒体詳細 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.detail')}}</label>
                    <div class="col-9">
                        <input name="media_detail" class="form-control form-control-sm form-control-plaintext" value="@if(isset($media->detail)){{$media->detail}}@endif">
                    </div>
                </div>
            </div>

            {{-- 金額 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.cost')}}</label>

                    <div class="col-9">
                        <div class="input-group-prepend">
                            <input name="media_cost" class="form-control form-control-sm form-control-plaintext" value="@if(isset($media->cost)){{$media->cost}}{{__('admin.item_name.common.wen')}}@endif">
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
                            <input name="media_date" class="form-control form-control-sm form-control-plaintext" value="@if(isset($media->date)){{$media->date}}@endif">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 広告開始時刻 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.start_time')}}</label>

                    <div class="col-9">
                        <input name="media_start_time" class="form-control form-control-sm form-control-plaintext" value="@if(isset($media->start_time)){{$media->start_time}}@endif">
                    </div>
                </div>
            </div>

            {{-- 放送時間分数 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.broadcast_minutes')}}</label>

                    <div class="col-9">
                        <div class="input-group-prepend">
                            <input name="media_broadcast_minutes" class="form-control form-control-sm form-control-plaintext" value="@if(isset($media->broadcast_minutes)){{$media->broadcast_minutes}}{{__('admin.item_name.common.minute')}}@endif">
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
                            <input name="media_broadcast_duration_from" class="form-control form-control-sm form-control-plaintext" value="{{$media->broadcast_duration_from}}@if(isset($media->broadcast_duration_to)) ~ {{$media->broadcast_duration_to}}@endif">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ラインナップ --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.product.lineup')}}</label>

                    <div class="col-9">
                        <input name="item_lineup_id" class="form-control form-control-sm form-control-plaintext" value="@if(isset($media->item_lineup_id)){{$itemLineupList[$media->item_lineup_id]}}@endif">
                    </div>
                </div>
            </div>

            {{-- 広告集計グループ --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.summary_group')}}</label>

                    <div class="col-9">
                        <input name="media_summary_group_id" class="form-control form-control-sm form-control-plaintext" value="@if(isset($media_summary_group_id)){{$mediaSummaryGroupList[$media_summary_group_id]}}@endif">
                    </div>
                </div>
            </div>

            {{-- 部数 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.media.circulation')}}</label>

                    <div class="col-9">
                        <div class="input-group-prepend">
                            <input name="media_circulation" class="form-control form-control-sm form-control-plaintext" value="@if(isset($media->circulation)){{$media->circulation}}{{__('admin.item_name.common.unit')}}@endif">
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
                            <input name="media_call_expected" class="form-control form-control-sm form-control-plaintext" value="@if(isset($media->call_expected)){{$media->call_expected}}{{__('admin.item_name.common.unit')}}@endif">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 備考 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.common.remark')}}</label>

                    <div class="col-9">
                        <textarea name="media_remark" class="form-control form-control-sm  form-control-plaintext" rows="3" readonly>@if(isset($media)){{$media->remark}}@endif</textarea>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

