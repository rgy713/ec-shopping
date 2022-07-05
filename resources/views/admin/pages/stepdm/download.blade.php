@extends('admin.layouts.main.contents')

@section('title') {{__('admin.page_title.stepdm_download')}} @endsection

@section('contents')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {{__('admin.page_header_name.stepdm_information')}}
                    <span class="specification" data-toggle="popover" data-placement="right" data-content="現行仕様通り。">&nbsp;</span>
                    <div class="card-header-actions">
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-sm table-hover">
                        <thead>
                        <tr>
                            <th>{{__('admin.item_name.stepdm_download.id')}}</th>
                            <th>{{__('admin.item_name.stepdm_download.executed_timestamp')}}</th>
                            <th>{{__('admin.item_name.stepdm_download.finished_timestamp')}}</th>
                            <th>{{__('admin.item_name.stepdm_download.total_count')}}</th>
                            <th>{{__('admin.item_name.stepdm_download.csv')}}</th>
                            <th>{{__('admin.item_name.stepdm_download.pdf')}}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($histories as $history)
                            <tr>
                                <td>{{$history->id}}</td>
                                <td>{{$history->executed_timestamp}}</td>
                                <td>{{$history->finished_timestamp}}</td>
                                <td>{{$history->total_count}}</td>
                                <td>
                                    @if($history->finished_timestamp)
                                        <button class="btn btn-sm btn-primary" onclick="downloadStepdmHistoryCsv({{$history->id}})"><i class="{{__("admin.icon_class.csv_download")}}"></i>&nbsp;{{__("admin.operation.csv_download")}}</button>
                                    @else
                                        {{__('admin.item_name.stepdm_download.progress')}}
                                    @endif
                                </td>
                                <td>
                                    @if($history->finished_timestamp)
                                        <button class="btn btn-sm btn-secondary" onclick="downloadStepdmHistoryPdf({{$history->id}})"><i class="{{__("admin.icon_class.csv_download")}}"></i>&nbsp;{{__('admin.item_name.stepdm_download.pdf')}}</button>
                                    @else
                                        {{__('admin.item_name.stepdm_download.progress')}}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <form id="downloadStepdmHistoryCsvForm" method="POST" action="{{route('admin.stepdm.download.csv')}}">
        {{ csrf_field() }}
        <input type="hidden" name="id">
    </form>
    <form id="downloadStepdmHistoryPdfForm" method="POST" action="{{route('admin.stepdm.download.pdf')}}">
        {{ csrf_field() }}
        <input type="hidden" name="id">
    </form>
@endsection
@push('content_js')
    <script>
        function downloadStepdmHistoryCsv(id){
            if(id) {
                const  $form = $("form#downloadStepdmHistoryCsvForm");
                $("form#downloadStepdmHistoryCsvForm input[name=id]").val(id);
                $form.submit();
            }
        }
        function downloadStepdmHistoryPdf(id){
            if(id) {
                const  $form = $("form#downloadStepdmHistoryPdfForm");
                $("form#downloadStepdmHistoryPdfForm input[name=id]").val(id);
                $form.submit();
            }
        }

    </script>
@endpush