@extends('admin.layouts.main.contents')

@section('title') {{__("admin.page_title.sales_age")}} @endsection

@section('contents')

    <div class="row" id="summary_result">
        {{-- 期間条件 start --}}
        <div class="col-12">
            @include('admin.components.sales.summary_period_form',
                [
                    'action'=>route('admin.sales.summary.age'),
                    'summary_term_year'=>isset($summary_term_year) ? $summary_term_year : '',
                    'summary_term_month'=>isset($summary_term_month) ? $summary_term_month : '',
                    'summary_term_from'=>isset($summary_term_from) ? $summary_term_from : '',
                    'summary_term_to'=>isset($summary_term_to) ? $summary_term_to : '',
                ])
        </div>
        {{-- 期間条件 end --}}

        @if (isset($summary1))
            <div class="col-12">
                @include('admin.components.sales.age.summary1', ['result'=>$summary1['summary'],'sum'=>$summary1['sum']])
            </div>

            <form id="csv_download_form" method="POST" accept-charset="UTF-8" action={{ route('admin.sales.summary.age.download_csv') }}>
                {{ csrf_field() }}
                <input name="term_from" type="hidden" value="{{$term_from}}">
                <input name="term_to" type="hidden" value="{{$term_to}}">
                <input name="age" type="hidden">
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
                csv_download: function(age) {
                    $("#csv_download_form input[name=age]").val(age);

                    $("#csv_download_form").submit();
                },
            }
        })
    </script>
@endpush

