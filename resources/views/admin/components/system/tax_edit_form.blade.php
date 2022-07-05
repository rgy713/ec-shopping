<div class="card">
    <div class="card-header">
        {{__('admin.page_header_name.system_tax_add')}}
        <div class="card-header-actions">
            <a class="card-header-action btn-close" href="#">

            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="col-12">
            <div class="row">
                {{-- 施行日付 --}}
                <div class="col-12 col-lg-12">
                    <div class="row form-group">
                        <label class="col-2 col-lg-2 col-form-label form-control-sm">{{__('admin.item_name.tax.activated_from')}}</label>
                        <div class="col-5 col-lg-5">
                            <input class="form-control form-control-sm @isInvalid($errors,'activated_from_date')" name="activated_from_date" type="date" placeholder="" value="{{old('activated_from_date')}}" required>
                            <div class="invalid-feedback">{{$errors->first('activated_from_date')}}</div>
                        </div>
                        <div class="col-5 col-lg-5">
                            <input class="form-control form-control-sm @isInvalid($errors,'activated_from_time')" name="activated_from_time" type="time" placeholder="" value="{{old('activated_from_time')}}" required>
                            <div class="invalid-feedback">{{$errors->first('activated_from_time')}}</div>
                        </div>
                    </div>
                </div>
                {{-- 税率 --}}
                <div class="col-12 col-lg-12">
                    <div class="row form-group">
                        <label class="col-2 col-lg-2 col-form-label form-control-sm">{{__('admin.item_name.tax.rate')}}</label>
                        <div class="col-10 col-lg-10">
                            <div class="input-group-prepend">
                                <input class="form-control form-control-sm @isInvalid($errors,'rate')" name="rate" type="number" min="1" max="100" step="1" placeholder="" value="{{old('rate')}}" required>
                                <span class="input-group-text form-control-sm">%</span>
                            </div>
                            <div class="invalid-feedback">{{$errors->first('rate')}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>