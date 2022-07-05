<div id="modal_holiday_regist_form" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="holiday_regist_form_app">
            <div class="modal-header">
                <h5 class="modal-title">{{__('admin.page_header_name.holiday_regist')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="holiday_regist_form" action="{{route('admin.system.holiday.create')}}" method="POST" accept-charset="UTF-8" @submit.prevent="onSubmit">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="row form-group">
                                <label class="col-4 col-form-label form-control-sm">{{__('admin.item_name.holiday.date')}}</label>
                                <div class="col-8 input-group-sm">
                                    <input :class="['form-control form-control-sm', errors.date ? 'is-invalid' : '']" type="date" name="date" value="" required/>
                                    <div v-if="errors.date" class="invalid-feedback">@{{  errors.date[0] }}</div>
                                </div>
                            </div>

                            <div class="row mb-sm-3">
                                <label class="col-4 col-form-label form-control-sm">{{__('admin.item_name.holiday.name')}}</label>
                                <div class="col-8">
                                    <input :class="['form-control form-control-sm', errors.holiday_name ? 'is-invalid' : '']" type="text" name="holiday_name" value="" onchange="return app.functions.trim(this);" required/>
                                    <div  v-if="errors.holiday_name" class="invalid-feedback">@{{ errors.holiday_name[0] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="{{__('admin.icon_class.back')}}"></i>&nbsp;{{__('admin.operation.back')}}</button>
                    <button type="submit" class="btn btn-primary"><i class="{{__('admin.icon_class.save')}}"></i>&nbsp;{{__('admin.operation.save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
