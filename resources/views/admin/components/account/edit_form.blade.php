@inject('adminRoleList', 'App\Common\KeyValueLists\AdminRoleList')

<div class="card">
    <div class="card-header">
        @if(isset($form_title)){{$form_title}}@endif
        <div class="card-header-actions">
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    {{-- 名前 --}}
                    <div class="col-12 col-lg-6">
                        <div class="row form-group">
                            <label class="col-2 col-lg-4 col-form-label form-control-sm">{{__("admin.item_name.account.name")}}</label>
                            <div class="col-10 col-lg-8">
                                <input class="form-control form-control-sm @isInvalid($errors,'name')" name="name" type="text" placeholder="" value="@if(old('name')){{ old('name') }}@elseif(isset($account)){{$account->name}}@endif" onchange="return app.functions.trim(this);" required>
                                <div class="invalid-feedback">{{$errors->first('name')}}</div>
                            </div>
                        </div>
                    </div>
                    {{-- 所属 --}}
                    <div class="col-12 col-lg-6">
                        <div class="row form-group">
                            <label class="col-2 col-lg-4 col-form-label form-control-sm">{{__("admin.item_name.account.department")}}</label>
                            <div class="col-10 col-lg-8">
                                <input class="form-control form-control-sm @isInvalid($errors,'department')" name="department" type="text" placeholder="" value="@if(old('department')){{ old('department') }}@elseif(isset($account)){{$account->department}}@endif" onchange="return app.functions.trim(this);" required>
                                <div class="invalid-feedback">{{$errors->first('department')}}</div>
                            </div>
                        </div>
                    </div>
                    {{-- account --}}
                    <div class="col-12 col-lg-6">
                        <div class="row form-group">
                            <label class="col-2 col-lg-4 col-form-label form-control-sm">{{__("admin.item_name.account.account")}}</label>
                            <div class="col-10 col-lg-8">
                                <input class="form-control form-control-sm @isInvalid($errors,'account')" name="account" type="text" pattern="^[a-zA-Z0-9!#$%&()*+,.:;=?@\[\]^_{}-]+$" title="{{__('validation.hint_text.alpha_num_symbol')}}" placeholder="" value="@if(old('account')){{old('account')}}@elseif(isset($account)){{$account->account}}@endif" onchange="return app.functions.trim(this);" required>
                                <div class="invalid-feedback">{{$errors->first('account')}}</div>
                            </div>
                        </div>
                    </div>
                    {{-- pass --}}
                    <div class="col-12 col-lg-6">
                        <div class="row form-group">
                            <label class="col-2 col-lg-4 col-form-label form-control-sm">{{__("admin.item_name.account.password")}}</label>
                            <div class="col-10 col-lg-8">
                                <input class="form-control form-control-sm @isInvalid($errors,'password')" name="password" type="password" pattern="^(?=.*?[a-zA-Z])(?=.*?\d)[a-zA-Z0-9!#$%&()*+,.:;=?@\[\]^_{}-]+$" title="{{__('validation.hint_text.alpha_num_symbol_both')}}" placeholder="" required>
                                <small class="form-text text-muted">{{__("admin.help_text.account.password")}}</small>
                                <div class="invalid-feedback">{{$errors->first('password')}}</div>
                            </div>
                        </div>
                    </div>

                    {{-- 権限 --}}
                    <div class="col-12 col-lg-6">
                        <div class="row form-group">
                            <label class="col-2 col-lg-4 col-form-label form-control-sm">{{__("admin.item_name.account.authority")}}</label>

                            <div class="col-10 col-lg-8">
                                <select class="form-control form-control-sm @isInvalid($errors,'admin_role_id')" name="admin_role_id" required>
                                    @foreach($adminRoleList as $id => $name)
                                        <option value="{{$id}}" @if(isset($account))@if($account->admin_role_id == $id)selected @endif @endif>{{$name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">{{$errors->first('admin_role_id')}}</div>
                            </div>
                        </div>
                    </div>

                    {{-- 有効/無効 --}}
                    <div class="col-12 col-lg-6">
                        <div class="row form-group">
                            <label class="col-2 col-lg-4 col-form-label form-control-sm">{{__("admin.item_name.account.enabled")}}</label>

                            <div class="col-10 col-lg-8 col-form-label form-control-sm">
                                <div class="form-check form-check-inline mr-1">
                                    <input id="admin-enabled-true" class="form-check-input @isInvalid($errors,'enabled')" name="enabled" type="radio" value="true" @if(isset($account))@if($account->enabled)checked @endif @else checked @endif required>
                                    <label class="form-check-label" for="admin-enabled-true">{{__("admin.item_name.account.enabled_true")}}</label>
                                </div>
                                <div class="form-check form-check-inline mr-1">
                                    <input id="admin-enabled-false" class="form-check-input @isInvalid($errors,'enabled')" name="enabled" type="radio" value="false" @if(isset($account))@if(!$account->enabled)checked @endif @endif required>
                                    <label class="form-check-label" for="admin-enabled-false">{{__("admin.item_name.account.enabled_false")}}</label>
                                </div>
                                <div class="invalid-feedback">{{$errors->first('enabled')}}</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

