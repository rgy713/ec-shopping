@extends('admin.layouts.main.contents')

@section('title') {{__("admin.page_title.order_search")}} @endsection

@section('contents')
    <div class="row" id="search-result-list">
        <form id="search_form" method="POST" accept-charset="UTF-8" action="{{route('admin.order.search')}}"  style="width: 100%;">
            {{ csrf_field() }}
            {{-- 受注検索条件指定フォーム --}}
            <div class="col-12">
                @include('admin.components.order.search_form')
            </div>

            {{-- 検索実行ボタン --}}
            <div class="col-12">
                @include('admin.components.common.search_footer')
            </div>
        </form>

        {{-- 結果表示 --}}
        @if (method_exists($orders, 'total'))
            <div class="col-12">
                <form id="result_form" method="POST" accept-charset="UTF-8" action="{{route('admin.order.update')}}">
                    {{ csrf_field() }}

                    @include('admin.components.order.search_result')
                </form>
            </div>
        @endif

        <form id="delete_form" method="POST" accept-charset="UTF-8" action="{{route('admin.order.delete')}}">
            {{ csrf_field() }}
        </form>

        <form id="csv_download_form" method="POST" accept-charset="UTF-8" action={{ route('admin.order.download_csv') }}>
            {{ csrf_field() }}
        </form>

    </div>
@endsection

@push('content_js')
    <script>
        new Vue({
            el: '#search-result-list',

            data: {
                sort: $('#search_form input[name=sort]').val(),
                page: $('#search_form input[name=page]').val(),
                order_id: $('#search_form input[name=order_id]').val(),
                order_customer_id: $('#search_form input[name=order_customer_id]').val(),
                order_customer_name: $('#search_form input[name=order_customer_name]').val(),
                status_chk_list: [],
                bundle_chk_list: [],
                delete_chk_list: [],
                all_status_chk: false,
                status_selected: '',
            },

            methods: {
                update_order_statuses: function() {
                    if (this.status_chk_list.length > 0) {
                        const data_form = new FormData($("#result_form")[0]);
                        data_form.set('update_type', 'order_statuses');

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
                    else {
                        alert("変更対象の受注が選択されていません");
                    }
                },
                update_order: function($order_id) {
                    const data_form = new FormData($("#result_form")[0]);
                    data_form.set('update_type', 'order');
                    data_form.set('order_id', $order_id);

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
                },
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
                delete_items: function() {
                    if (this.delete_chk_list.length > 0) {
                        if (confirm("本当に削除しますか？")) {
                            const data_form = new FormData($("#delete_form")[0]);
                            const form_action = $("#delete_form").attr('action');

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
                check_all_status: function (event) {
                    if (this.all_status_chk) {
                        this.status_chk_list = $('#search_list_table .status_chk_list').map(function() {
                            return this.value;
                        }).toArray();
                    }
                    else {
                        this.status_chk_list = [];
                    }
                },
                csv_download: function() {
                    $("form#search_form :input").not(':submit').clone().hide().appendTo("form#csv_download_form");

                    $("#csv_download_form").submit();
                },
            }
        });
    </script>
@endpush