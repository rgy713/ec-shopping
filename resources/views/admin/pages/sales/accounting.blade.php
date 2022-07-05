@extends('admin.layouts.main.contents')

@section('title') {{__("admin.page_title.sales_accounting")}} @endsection

@section('contents')

    <div class="row" id="summary_result">

        {{-- 期間条件 start --}}
        <div class="col-12">
            @include('admin.components.sales.summary_period_form',
                [
                    'action'=>route('admin.sales.summary.accounting'),
                    'summary_term_year'=>isset($summary_term_year) ? $summary_term_year : '',
                    'summary_term_month'=>isset($summary_term_month) ? $summary_term_month : '',
                    'summary_term_from'=>isset($summary_term_from) ? $summary_term_from : '',
                    'summary_term_to'=>isset($summary_term_to) ? $summary_term_to : '',
                ])
        </div>
        {{-- 期間条件 end --}}

        @if (isset($summary1))
            <div class="col-12">
                @include('admin.components.sales.accounting.summary1',
                    [ 'head_name'=>'1.' .  __("admin.page_header_name.summary_accounting_summary1"), 'accounting_type'=>1, 'summary'=>$summary1])
            </div>
        @endif

        @if (isset($summary2))
            <div class="col-12">
                @include('admin.components.sales.accounting.summary1',
                    [ 'head_name'=>'2.' .  __("admin.page_header_name.summary_accounting_summary2"), 'accounting_type'=>2, 'summary'=>$summary2])
            </div>
        @endif

        @if (isset($summary3))
            <div class="col-12">
                @include('admin.components.sales.accounting.summary3',
                    [ 'head_name'=>'3.' .  __("admin.page_header_name.summary_accounting_summary3"), 'accounting_type'=>3, 'summary'=>$summary3])
            </div>
        @endif

        @if (isset($summary4))
            <div class="col-12">
                @include('admin.components.sales.accounting.summary4',
                    [ 'head_name'=>'4.' .  __("admin.page_header_name.summary_accounting_summary4"), 'accounting_type'=>4, 'summary'=>$summary4])
            </div>
        @endif

        @if (isset($summary5))
            <div class="col-12">
                @include('admin.components.sales.accounting.summary4',
                    [ 'head_name'=>'5.' .  __("admin.page_header_name.summary_accounting_summary5"), 'accounting_type'=>5, 'summary'=>$summary5])
            </div>
        @endif

        <form id="csv_download_form" method="POST" accept-charset="UTF-8" action={{ route('admin.sales.summary.accounting.download_csv') }}>
            {{ csrf_field() }}
            <input name="term_from" type="hidden" value="@if (isset($term_from)) {{$term_from}} @endif">
            <input name="term_to" type="hidden" value="@if (isset($term_to)) {{$term_to}} @endif">
            <input name="accounting_type" type="hidden">
        </form>
    </div>
@endsection

@push('content_js')
    <script>
        const adminVue = new Vue({
            el: '#summary_result',

            data: {

            },

            methods: {
                csv_download: function(accounting_type) {
                    $("#csv_download_form input[name=accounting_type]").val(accounting_type);

                    $("#csv_download_form").submit();
                },
            }
        })
    </script>
@endpush

