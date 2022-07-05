<div class="card">
    <div class="card-header">
        {{__("admin.page_header_name.summary_periodic_csv_download")}}
        <div class="card-header-actions">

        </div>
    </div>

    <div class="card-body">
        <div class="row">
                <input name="term" type="hidden" value="term" />

                <div class="col-12">
                    <div class="row form-group">
                        <label class="col-4 col-form-label col-form-label-sm">{{__('admin.item_name.sales.period')}}</label>
                        <div class="col-4 pr-0">
                            <div class="input-group-prepend">
                                <input name="summary_term_from" type="date" class="form-control form-control-sm @isInvalid($errors,'summary_term_from')" required onchange="summary_term_from_change();"
                                       v-model="summary_term_from" value="@if (old('summary_term_from')){{old('summary_term_from')}}@endif">
                                <span class="input-group-text form-control-sm">～</span>
                            </div>
                            <div class="invalid-feedback">{{$errors->first('summary_term_from')}}</div>
                        </div>

                        <div class="col-4 pl-0">
                            <div class="input-group-prepend">
                                <input name="summary_term_to" type="date" class="form-control form-control-sm @isInvalid($errors,'summary_term_to')" required onchange="summary_term_to_change();"
                                       v-model="summary_term_to" value="@if (old('summary_term_to')){{old('summary_term_to')}}@endif">
                            </div>
                            <div class="invalid-feedback">{{$errors->first('summary_term_to')}}</div>
                        </div>
                    </div>
                </div>


                {{--  --}}
                <div class="col-12">
                    <div class="row form-group">
                        <label class="col-4 col-form-label col-form-label-sm">{{__('admin.item_name.sales.export_setting')}}</label>
                        <div class="col-8 pr-0 col-form-label form-control-sm">
                            <div class="form-check form-check-inline mr-1">
                                <input name="export_type" type="radio" id="radio-01" class="form-check-input" value="0" required
                                       @if (old('export_type')==0) checked @endif>
                                <label class="form-check-label" for="radio-01">{{__('admin.item_name.sales.export_month')}}</label>
                            </div>

                            <div class="form-check form-check-inline mr-1">
                                <input name="export_type" type="radio" id="radio-02" class="form-check-input" value="1"
                                       @if (old('export_type')==1) checked @endif>
                                <label class="form-check-label" for="radio-02">{{__('admin.item_name.sales.export_all')}}</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 offset-3">
                    <button class="btn btn-block btn-sm btn-primary" type="submit">{{__('admin.operation.download')}}</button>
                </div>
        </div>
    </div>

</div>