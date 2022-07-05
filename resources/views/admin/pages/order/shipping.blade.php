@extends('admin.layouts.main.contents')
@inject('orderStatusList', 'App\Common\KeyValueLists\OrderStatusList')
@inject('paymentList', 'App\Common\KeyValueLists\PaymentList')
@inject('searchResultNumList', 'App\Common\KeyValueLists\SearchResultNumList')

@section('title') {{__("admin.page_title.shipping_search")}} @endsection

@section('contents')
    <div class="row" id="search-result-list">
        <form id="search_form" method="POST" accept-charset="UTF-8" action="{{route('admin.order.shipping.search_result')}}"  style="width: 100%;">
            {{ csrf_field() }}

            <input name="is_shipping" value="true" type="hidden">
            {{-- 受注検索条件指定フォーム --}}
            <div class="col-12">
                @include('admin.components.order.search_form')
            </div>

            {{-- 検索実行ボタン --}}
            <div class="col-12">
                @include('admin.components.common.search_footer')
            </div>
        </form>
        @if(isset($orders))
            {{-- 結果表示 --}}
            <div class="col-12">
                @include('admin.components.order.search_result')
            </div>
        @endif
        <form id="csv_download_form" method="POST" accept-charset="UTF-8" action={{ route('admin.order.shipping.download_csv') }}>
            {{ csrf_field() }}
            <input name="is_shipping" value="true" type="hidden">
        </form>
        <form id="delivery_pdf_form" method="POST" accept-charset="UTF-8" action={{ route('admin.order.shipping.delivery_pdf') }}>
            {{ csrf_field() }}
        </form>
    </div>

@endsection

@push('content_js')
    <script>
        var adminPaginationComponent = new Vue({
            el: '#search-result-list',

            data: {
                sort: $('#search_form input[name=sort]').val(),
                page: $('#search_form input[name=page]').val(),
                order_id: $('#search_form input[name=order_id]').val(),
                order_customer_id: $('#search_form input[name=order_customer_id]').val(),
                order_customer_name: $('#search_form input[name=order_customer_name]').val(),
                export_chk_list: [],
                bundle_chk_list: [],
                all_export_chk: false,
            },

            methods: {
                bundle_items: function() {
                    if (this.bundle_chk_list.length > 0) {
                        const data_form = new FormData($("#result_form")[0]);
                        data_form.set('update_type', 'bundle');

                        const form_action = $("#result_form").attr('action');

                        axios.post(form_action, data_form)
                            .then(response => {
                                if (response.data && response.data.status == 'success') {
                                    window.location.reload();
                                }
                            })
                            .catch(error => {
                                console.log(error);
                            });
                    }
                },
                search_result_new: function() {
                    $('#search_form input[name=page]').val('');
                    $('#search_form input[name=sort]').val('');

                    $('#search_form').submit();
                },
                search_results: function(page, sort) {
                    if (page) {
                        $('#search_form input[name=page]').val(page);
                    }
                    if (sort) {
                        $('#search_form input[name=sort]').val(sort);
                    }

                    $('#search_form').submit();
                },
                reset_form: function () {
                    app.functions.resetForm('#search_form');
                    $('#page_input').val('');
                    $('#sort_input').val('');
                    $("#number_per_page").val($("#number_per_page option:first").val());
                },
                check_all_export: function (event) {
                    if (this.all_export_chk) {
                        this.export_chk_list = $('#search_list_table .export_chk_list').map(function() {
                            return this.value;
                        }).toArray();
                    }
                    else {
                        this.export_chk_list = [];
                    }
                },
                csv_download: function() {
                    $("#csv_download_form").submit();
                },
                delivery_pdf:function(order_id){
                    if(order_id){
                        $("#delivery_pdf_form input.export_chk_list").remove();
                        const $form = $("#delivery_pdf_form");
                        $form.append('<input name="export_chk_list[]" type="hidden" value="' + order_id + '">');
                        $form.submit();
                    }
                },
                delivery_pdf_list:function(){
                    if(this.export_chk_list.length>0) {
                        $("#delivery_pdf_form input.export_chk_list").remove();
                        const $form = $("#delivery_pdf_form");
                        for (var i = 0; i < this.export_chk_list.length; i++){
                            $form.append('<input name="export_chk_list[]" type="hidden" value="' + this.export_chk_list[i] + '">');
                        }
                        $form.submit();
                    }
                }
            }
        });
    </script>
@endpush