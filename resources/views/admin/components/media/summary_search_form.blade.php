@inject('mediaCodeGroupList', 'App\Common\KeyValueLists\MediaCodeGroupList')

{{-- 総合分析条件入力フォーム --}}
<div class="card">
    <div class="card-header">
        集計条件設定
        <div class="card-header-actions">
        </div>
    </div>
    <div class="card-body">

        <div class="row">
            {{-- 広告日 --}}
            <div class="col-12">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__('admin.item_name.media.start_date')}}</label>
                    <div class="col-sm-5 pr-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm" name="" type="date" placeholder="">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                    </div>
                    <div class="col-sm-5 pl-0">
                        <input class="form-control form-control-sm" name="" type="date" placeholder="">
                    </div>
                </div>
            </div>

            {{-- 広告番号 --}}
            <div class="col-6">
                <div class="row form-group">
                    <label class="col-4 col-form-label col-form-label-sm">{{__('admin.item_name.media.code')}}</label>
                    <div class="col-8">
                        <div class="input-group">
                            <input class="form-control form-control-sm" name="" type="number" placeholder="">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 媒体名 --}}
            <div class="col-6">
                <div class="row form-group">
                    <label class="col-4 col-form-label col-form-label-sm">{{__('admin.item_name.media.name')}}</label>
                    <div class="col-8">
                        <div class="input-group">
                            <input class="form-control form-control-sm" name="" type="number" placeholder="">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 媒体別 --}}
            <div class="col-12">
                <div class="row form-group">
                    <label class="col-2 col-form-label col-form-label-sm">{{__('admin.item_name.media.code_group')}}</label>
                    <div class="col-10">
                        @foreach($mediaCodeGroupList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="media_summary_search_form-{{$id}}" value="{{$id}}">
                                <label for="media_summary_search_form-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>

            {{-- 集計設定 --}}
            <div class="col-6">
                <div class="row form-group">
                    <label class="col-4 col-form-label col-form-label-sm">集計設定</label>
                    <div class="col-8">
                        <div class="form-check form-check-inline form-control-sm">
                            <input type="radio" class="form-check-input">
                            <label for="" class="form-check-label">総合分析を出力</label>
                        </div>
                        <div class="form-check form-check-inline form-control-sm">
                            <input type="radio" class="form-check-input">
                            <label for="" class="form-check-label">定期分析を出力</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 出力設定 --}}
            <div class="col-6">
                <div class="row form-group">
                    <label class="col-4 col-form-label col-form-label-sm">出力設定</label>
                    <div class="col-8">
                        <div class="form-check form-check-inline form-control-sm">
                            <input type="radio" class="form-check-input">
                            <label for="" class="form-check-label">通常表示</label>
                        </div>
                        <div class="form-check form-check-inline form-control-sm">
                            <input type="radio" class="form-check-input">
                            <label for="" class="form-check-label">印刷用レイアウトで表示</label>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>