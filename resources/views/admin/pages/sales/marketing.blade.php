@extends('admin.layouts.main.contents')

@section('title') {{__("admin.page_title.sales_marketing")}} @endsection

@section('contents')

    <div class="row" id="summary_result">
        {{-- 期間条件 start --}}
        <div class="col-12">
            @include('admin.components.sales.summary_period_form',
                [
                    'action'=>route('admin.sales.summary.marketing'),
                    'summary_term_year'=>isset($summary_term_year) ? $summary_term_year : '',
                    'summary_term_month'=>isset($summary_term_month) ? $summary_term_month : '',
                    'summary_term_from'=>isset($summary_term_from) ? $summary_term_from : '',
                    'summary_term_to'=>isset($summary_term_to) ? $summary_term_to : '',
                ])
        </div>
        {{-- 期間条件 end --}}

        @if (isset($summary1))
            <div class="col-12">
                @include('admin.components.sales.marketing.summary1',
                    [ 'head_name'=>__("admin.page_header_name.summary_marketing_summary1"), 'marketing_type'=>1, 'summary'=>$summary1])
            </div>
        @endif

        @if (isset($summary2))
            <div class="col-12">
                @include('admin.components.sales.marketing.summary2',
                    [ 'head_name'=>__("admin.page_header_name.summary_marketing_summary2"), 'marketing_type'=>2, 'summary'=>$summary2])
            </div>
        @endif

        @if (isset($summary3))
            <div class="col-12">
                @include('admin.components.sales.marketing.summary3',
                    [ 'head_name'=>__("admin.page_header_name.summary_marketing_summary3"), 'marketing_type'=>3, 'summary'=>$summary3])
            </div>
        @endif

        @if (isset($summary4))
            <div class="col-6">
                @include('admin.components.sales.marketing.summary4',
                    [ 'head_name'=>__("admin.page_header_name.summary_marketing_summary4"), 'marketing_type'=>4, 'summary'=>$summary4])
            </div>
        @endif

        @if (isset($summary5))
            <div class="col-6">
                @include('admin.components.sales.marketing.summary5',
                    [ 'head_name'=>__("admin.page_header_name.summary_marketing_summary5"), 'marketing_type'=>5, 'summary'=>$summary5])
            </div>
        @endif

        <form id="csv_download_form" method="POST" accept-charset="UTF-8" action={{ route('admin.sales.summary.marketing.download_csv') }}>
            {{ csrf_field() }}
            <input name="term_from" type="hidden" value="@if (isset($term_from)) {{$term_from}} @endif">
            <input name="term_to" type="hidden" value="@if (isset($term_to)) {{$term_to}} @endif">
            <input name="marketing_type" type="hidden">
        </form>

    </div>

@endsection

@push('content_js')
    <script>
        new Vue({
            el: '#summary_result',

            data: {

            },

            methods: {
                csv_download: function(marketing_type) {
                    $("#csv_download_form input[name=marketing_type]").val(marketing_type);

                    $("#csv_download_form").submit();
                },
            }
        })
    </script>
@endpush

