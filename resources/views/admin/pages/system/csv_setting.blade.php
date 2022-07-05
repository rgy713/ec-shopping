@extends('admin.layouts.main.contents')

@section('title') システム設定 > CSV出力項目設定 @endsection

@section('contents')
    <form id="edit_csv_type" method="POST" action="{{route('admin.system.csv_setting', ['id'=>$csvType->id])}}" style="width: 100%;">
        {{csrf_field()}}
        <div class="row">
            <div class="col-12">
                @include('admin.components.system.csv_setting_form')
            </div>
        </div>

        {{-- ボタン --}}
        <div class="row">
            <div class="col-12">
                <div class="row mb-3">
                    <div class="col-6 offset-3">
                        <button class="btn btn-sm btn-block btn-primary" type="button" v-on:click="onSubmit">
                            <i class="{{__('admin.icon_class.save')}}"></i>&nbsp;{{__('admin.operation.save')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- ボタン --}}
    </form>
@endsection
@push('content_js')
    <script>
        new Vue({
            el: '#edit_csv_type',
            data: function(){
                return {
                    errors:{},
                }
            },
            methods: {
                double_up:function(){
                    var $op = $('#output_enabled option:selected');
                    if($op.length){
                        $('#output_enabled').prepend($op)
                    }
                },
                up:function(){
                    var $op = $('#output_enabled option:selected');
                    if($op.length){
                        $op.first().prev().before($op);
                    }
                },
                down:function(){
                    var $op = $('#output_enabled option:selected');
                    if($op.length){
                        $op.last().next().after($op);
                    }
                },
                double_down:function(){
                    var $op = $('#output_enabled option:selected');
                    if($op.length){
                        $('#output_enabled').append($op)
                    }
                },
                right:function(){
                    var $op = $('#output_enabled option:selected');
                    if($op.length){
                        $('#output_disabled').append($op)
                    }
                },
                double_right:function(){
                    var $op = $('#output_enabled option');
                    if($op.length){
                        $('#output_disabled').append($op)
                    }
                },
                left:function(){
                    var $op = $('#output_disabled option:selected');
                    if($op.length){
                        $('#output_enabled').append($op)
                    }
                },
                double_left:function(){
                    var $op = $('#output_disabled option');
                    if($op.length){
                        $('#output_enabled').append($op)
                    }
                },
                onSubmit:function(){
                    const form = $("#edit_csv_type")[0];
                    var action = form.action;
                    const dataform = new FormData(form);
                    const $enabled_op = $('#output_enabled option');
                    for(var i=0;i<$enabled_op.length;i++){
                        const one = $enabled_op[i];
                        dataform.append('item['+ one.value +']', true);
                        dataform.append('rank['+ one.value +']', i + 1);
                    }
                    const $disabled_op = $('#output_disabled option');
                    for(var i=0;i<$disabled_op.length;i++){
                        const one = $disabled_op[i];
                        dataform.append('item['+ one.value +']', false);
                        dataform.append('rank['+ one.value +']', $enabled_op.length + i + 1);
                    }
                    axios.post(action, dataform)
                        .then( response => {
                            if(response.data.status=="error"){
                                this.errors = response.data.saved;
                            }else{
                                this.errors = {};
                            }
                        })
                        .catch((error) => {
                            this.errors = error.response.data.errors;
                        });
                }
            }
        })
    </script>
@endpush