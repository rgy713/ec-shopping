@extends('admin.layouts.main.contents')

@section('title') {{__("admin.page_title.sales_wholesale")}} @endsection

@section('contents')

    <div class="row" id="summary_result">
        {{-- 期間条件 start --}}
        <div class="col-12">
            @include('admin.components.sales.summary_period_form',
                [
                    'action'=>route('admin.sales.summary.wholesale'),
                    'summary_term_year'=>isset($summary_term_year) ? $summary_term_year : '',
                    'summary_term_month'=>isset($summary_term_month) ? $summary_term_month : '',
                    'summary_term_from'=>isset($summary_term_from) ? $summary_term_from : '',
                    'summary_term_to'=>isset($summary_term_to) ? $summary_term_to : '',
                ])
        </div>
        {{-- 期間条件 end --}}
        @if (isset($summary1))
            <div class="col-12">
                @include('admin.components.sales.wholesale.summary1', ['result'=>$summary1['summary'],'sum'=>$summary1['sum']])
            </div>
        @endif

        @if (isset($summary2))
            <div class="col-12">
                @include('admin.components.sales.wholesale.summary2', ['result'=>$summary2])
            </div>
        @endif

        @if (isset($summary1) || isset($summary2))
            <form id="csv_download_form" method="POST" accept-charset="UTF-8" action={{ route('admin.sales.summary.wholesale.download_csv') }}>
                {{ csrf_field() }}
                <input name="term_from" type="hidden" value="{{$term_from}}">
                <input name="term_to" type="hidden" value="{{$term_to}}">
                <input name="customer_id" type="hidden">
            </form>
        @endif

    </div>

@endsection

@push('content_js')
    <script>
        const adminVue = new Vue({
            el: '#summary_result',

            data: {

            },

            methods: {
                csv_download: function(customer_id) {
                    $("#csv_download_form input[name=customer_id]").val(customer_id);

                    $("#csv_download_form").submit();
                },
            }
        })
    </script>
@endpush