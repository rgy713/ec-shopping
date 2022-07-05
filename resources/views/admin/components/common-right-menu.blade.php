{{-- 右メニューのタブ --}}
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#timeline" role="tab">
            <i class="icon-list"></i>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#messages" role="tab">
            <i class="icon-wrench"></i>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#settings" role="tab">
            <i class="{{__("admin.icon_class.setting")}}"></i>
        </a>
    </li>
</ul>

{{-- 右メニューのタブ内 --}}
<div class="tab-content">
    {{-- タブ1 --}}
    <div class="tab-pane active" id="timeline" role="tabpanel">
        <div class="list-group list-group-accent">
            {{-- 当日分ログ --}}
            <div class="list-group-item list-group-item-accent-secondary bg-light text-center font-weight-bold text-muted text-uppercase small">
                Today
            </div>
            @if(count($systemLogsToday) > 0)
                @foreach($systemLogsToday as $systemLog)
                    <div class="list-group-item list-group-item-accent-{{$systemLog->data['level']}} list-group-item-divider">
                        <div>
                            <small>{{$systemLog->data['message']}}</small>
                        </div>
                        <small class="text-muted mr-3">
                            <i class="icon-calendar"></i>&nbsp;@datetime($systemLog->created_at)
                        </small>

                        <small class="text-muted">
                            <i class="icon-info"></i>&nbsp;{{$systemLog->data['level']}}
                        </small>
                    </div>
                @endforeach
            @else
                <div class="list-group-item list-group-item-accent-warning list-group-item-divider">
                    <div>
                        <small>システムログデータが見つかりませんでした。</small>
                    </div>
                    <small class="text-muted mr-3">
                        <i class="icon-calendar"></i>&nbsp;no data
                    </small>

                    <small class="text-muted">
                        <i class="icon-info"></i>&nbsp;warning
                    </small>
                </div>
            @endif

            {{-- 前日分ログ --}}
            <div class="list-group-item list-group-item-accent-secondary bg-light text-center font-weight-bold text-muted text-uppercase small">
                Yesterday
            </div>

            @if(count($systemLogsYesterday) > 0)
                @foreach($systemLogsYesterday as $systemLog)
                    <div class="list-group-item list-group-item-accent-{{$systemLog->data['level']}} list-group-item-divider">
                        <div>
                            <small>{{$systemLog->data['message']}}</small>
                        </div>
                        <small class="text-muted mr-3">
                            <i class="icon-calendar"></i>&nbsp;@datetime($systemLog->created_at)
                        </small>

                        <small class="text-muted">
                            <i class="icon-info"></i>&nbsp;{{$systemLog->data['level']}}
                        </small>
                    </div>
                @endforeach
            @else

                <div class="list-group-item list-group-item-accent-warning list-group-item-divider">
                    <div>
                        <small>システムログデータが見つかりませんでした。</small>
                    </div>
                    <small class="text-muted mr-3">
                        <i class="icon-calendar"></i>&nbsp;no data
                    </small>

                    <small class="text-muted">
                        <i class="icon-info"></i>&nbsp;warning
                    </small>
                </div>
            @endif

        </div>
    </div>

    {{-- システム情報 --}}
    <div class="tab-pane p-3" id="messages" role="tabpanel">

        @foreach($systemInfo as $product => $item)
            <div class="message">
                <div class="row">
                    <div class="col-4">
                        <img class="rounded img-fluid" src="{{$item['logo']}}" alt="">
                    </div>
                    <div class="col-8">
                        <div class="text-truncate font-weight-bold">{{$product}} {{$item['version']}}</div>
                        <small class="text-muted">{{$item['info']}}</small>
                    </div>
                </div>
            </div>
            <hr>
        @endforeach

        {{--timezone 設定表示--}}
        <div class="message">
            <div class="row">
                <div class="col-12">
                    {{--System timezone 現在時刻--}}
                    <div class="text-truncate font-weight-bold">System Timezone:{{Config::get("app.timezone")}}</div>
                    <small class="text-muted">{{\Carbon\Carbon::now()}}</small>
                </div>
            </div>
        </div>

    </div>

    <div class="tab-pane p-3 admin-settings" id="settings" role="tabpanel">
        <div id="admin-settings">
            <admin-setting-component></admin-setting-component>
        </div>
    </div>

</div>
