@extends('admin.layouts.main.contents')

@section('title') {{__('admin.page_title.system_tax')}} @endsection

@section('contents')
    <div class="row">
        <div class="col-12">
            @include('admin.components.system.tax_list')
        </div>
    </div>
    @if($admin->admin_role_id==1 or $admin->admin_role_id==2)
        <form method="POST" accept-charset="UTF-8" action="{{route('admin.system.tax.create')}}" >
            {{ csrf_field() }}
            <div class="row">
                <div class="col-12">
                    @include('admin.components.system.tax_edit_form')
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @include("admin.components.common.form_save_button")
                </div>
            </div>
        </form>
    @endif

    <form id="tax_delete_form" method="POST" action="{{route('admin.system.tax.delete')}}">
        {{ csrf_field() }}
        <input type="hidden" name="id">
    </form>
@endsection

@push('content_js')
    <script>
        {{--税率設定を削除し--}}
        function tax_delete(id){
            if(id) {
                if (confirm("削除しますか？")) {
                    const $form = $("form#tax_delete_form");
                    $("form#tax_delete_form input[name=id]").val(id);
                    $form.submit();
                }
            }
        }
    </script>
@endpush