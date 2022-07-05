{{-- 集計期間指定用フォーム --}}
<div class="card">
    {{-- header start --}}
    <div class="card-header">
        {{__('admin.page_header_name.summary_term')}}
        <div class="card-header-actions">
            <label class="switch-sm switch-label switch-outline-primary-alt" role="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="true" aria-controls="search-product-body search-product-footer">
            </label>
        </div>
    </div>
    {{-- body start --}}
    <div class="card-body">
        <div class="row" id="term_form_body">
            {{--  --}}

            <div class="col-12">
                <form id="term_form1" method="POST" accept-charset="UTF-8" action={{ isset($action) ? $action : '' }}>
                    {{ csrf_field() }}
                    <input name="term" type="hidden" value="month" />

                    <div class="row form-group">
                        <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.sales.monthly')}}</label>
                        <div class="col-3 pr-0">
                            <div class="input-group-prepend">
                                <input name="summary_term_year" type="number" min="2000" max="2999" class="form-control form-control-sm @isInvalid($errors,'summary_term_year')" onchange="javascript:summary_term_year_change();" onkeydown="app.functions.only_number_key(event);"
                                       value="@if (old('summary_term_year')){{old('summary_term_year')}}@elseif (isset($summary_term_year)){{$summary_term_year}}@endif">
                                <span class="input-group-text form-control-sm">{{__('admin.item_name.common.year')}}</span>
                            </div>
                        </div>

                        <div class="col-3 pl-0">
                            <div class="input-group-prepend">
                                <input name="summary_term_month" type="number" min="1" max="12" class="form-control form-control-sm @isInvalid($errors,'summary_term_month')" onchange="javascript:summary_term_month_change();" onkeydown="app.functions.only_number_key(event);"
                                       value="@if (old('summary_term_month')){{old('summary_term_month')}}@elseif (isset($summary_term_month)){{$summary_term_month}}@endif">
                                <span class="input-group-text form-control-sm">{{__('admin.item_name.sales.monthly')}}</span>
                            </div>
                        </div>

                        <div class="col-3">
                            <button class="btn btn-sm btn-block btn-primary">{{__('admin.operation.summary')}}</button>
                        </div>
                    </div>
                </form>
            </div>


            <div class="col-12">
                <form id="term_form2" method="POST" accept-charset="UTF-8" action={{ isset($action) ? $action : '' }}>
                    {{ csrf_field() }}
                    <input name="term" type="hidden" value="term" />

                    <div class="row form-group">
                        <label class="col-3 col-form-label col-form-label-sm">{{__('admin.item_name.sales.period')}}</label>
                        <div class="col-3 pr-0">
                            <div class="input-group-prepend">
                                <input name="summary_term_from" type="date" class="form-control form-control-sm @isInvalid($errors,'summary_term_from')" onchange="javascript:summary_term_from_change();"
                                       value="@if (old('summary_term_from')){{old('summary_term_from')}}@elseif (isset($summary_term_from)){{$summary_term_from}}@endif">
                                <span class="input-group-text form-control-sm">～</span>
                            </div>
                        </div>

                        <div class="col-3 pl-0">
                            <div class="input-group-prepend">
                                <input name="summary_term_to" type="date" class="form-control form-control-sm @isInvalid($errors,'summary_term_to')" onchange="javascript:summary_term_to_change();"
                                       value="@if (old('summary_term_to')){{old('summary_term_to')}}@elseif (isset($summary_term_to)){{$summary_term_to}}@endif">
                            </div>
                        </div>

                        <div class="col-3">
                            <button class="btn btn-sm btn-block btn-primary">{{__('admin.operation.summary')}}</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@push('content_js')
    <script>
        function summary_term_year_change() {
            $('input[name=summary_term_month]').attr('required', !!$('input[name=summary_term_year]').val());
        }
        function summary_term_month_change() {
            $('input[name=summary_term_year]').attr('required', !!$('input[name=summary_term_month]').val());
        }

        function summary_term_from_change() {
            $('input[name=summary_term_to]').attr('min', $('input[name=summary_term_from]').val());
        }
        function summary_term_to_change() {
            $('input[name=summary_term_from]').attr('max', $('input[name=summary_term_to]').val());
        }

        summary_term_year_change();
        summary_term_month_change();
        summary_term_from_change();
        summary_term_to_change();
    </script>
@endpush
