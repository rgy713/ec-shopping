@extends('admin.layouts.main.contents')

@section('title') {{__("admin.page_title.sales_periodic")}} @endsection

@section('contents')

    <div class="row" id="summary_result">
        <div class="col-12">
            <form id="csv_download_form" method="POST" accept-charset="UTF-8" action={{ route('admin.sales.summary.periodic_count.download_csv') }}>
                {{ csrf_field() }}
                @include('admin.components.sales.periodic_count.download')
            </form>
        </div>

        <div class="col-12">
            @include('admin.components.sales.periodic_count.summary', ['header_name'=>__("admin.page_header_name.summary_periodic_summary1"), 'summary'=>$summary1['summary'],'sum'=>$summary1['sum']])
        </div>

        <div class="col-12">
            @include('admin.components.sales.periodic_count.summary', ['header_name'=>__("admin.page_header_name.summary_periodic_summary2"), 'summary'=>$summary2['summary'],'sum'=>$summary2['sum']])
        </div>

        <div class="col-12">
            @include('admin.components.sales.periodic_count.summary', ['header_name'=>__("admin.page_header_name.summary_periodic_summary3"), 'summary'=>$summary3['summary'],'sum'=>$summary3['sum']])
        </div>

        <div class="col-12">
            @include('admin.components.sales.periodic_count.summary', ['header_name'=>__("admin.page_header_name.summary_periodic_summary4"), 'summary'=>$summary4['summary'],'sum'=>$summary4['sum']])
        </div>

        <div class="col-12">
            @include('admin.components.sales.periodic_count.summary', ['header_name'=>__("admin.page_header_name.summary_periodic_summary5"), 'summary'=>$summary5['summary'],'sum'=>$summary5['sum']])
        </div>

    </div>

@endsection

@push('content_js')
    <script>
        new Vue({
            el: '#summary_result',

            data: {
                summary_term_from: $('input[name=summary_term_year]').val(),
                summary_term_to: $('input[name=summary_term_from]').val(),
            },

            methods: {

            }
        })

        function summary_term_from_change() {
            $('input[name=summary_term_to]').attr('min', $('input[name=summary_term_from]').val());
        }
        function summary_term_to_change() {
            $('input[name=summary_term_from]').attr('max', $('input[name=summary_term_to]').val());
        }

        summary_term_from_change();
        summary_term_to_change();
    </script>
@endpush
