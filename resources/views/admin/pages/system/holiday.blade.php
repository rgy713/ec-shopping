@extends('admin.layouts.main.contents')

@section('title') {{__('admin.page_title.system_holiday')}} @endsection

@section('contents')
    <form  id="holidaysForm" method="POST" accept-charset="UTF-8">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-12">
                @include('admin.components.system.holiday_edit_form')
            </div>
        </div>
        @if($admin->admin_role_id==1 or $admin->admin_role_id==2)
            {{-- ボタン --}}
            <div class="row">
                <div class="col-12">
                    <div class="row mb-3">
                        <div class="col-6 offset-3">
                            <button class="btn btn-sm btn-block btn-primary" type="submit">
                                <i class="{{__('admin.icon_class.save')}}"></i>&nbsp;{{__('admin.operation.save')}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ボタン --}}
        @endif
    </form>
    @include('admin.components.system.holiday_regist_form')
@endsection

@push('content_js')
    {{--TODO--}}
    <script>
        function holidays_delete(action) {
            if (action) {
                if (confirm("削除しますか？")) {
                    const $form = $("form#holidaysForm");
                    $form.attr("action", action);
                    $form.submit();
                }
            }
        }

        const holiday_regist_form = new Vue({
            el: '#holiday_regist_form_app',
            data: function(){
                return {
                    errors:{}
                }
            },
            methods : {
                onSubmit:function() {
                    const action = $("#holiday_regist_form").attr("action");
                    const dataform = new FormData($("#holiday_regist_form")[0]);
                    axios.post(action, dataform)
                        .then( response => {
                            if(response.data.status=="error"){
                                this.errors = response.data.saved;
                            }else{
                                this.errors = {};
                                window.location.reload();
                            }
                        })
                        .catch((error) => {
                            this.errors = error.response.data.errors;
                        });
                }
            }
        });

    </script>
@endpush