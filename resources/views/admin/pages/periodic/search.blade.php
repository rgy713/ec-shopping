{{-- 定期詳細、通常画面とポップアップ、異なるレイアウトで同じコンテンツを表示するため、contensセクションはinclude --}}
@extends('admin.layouts.main.contents')

@section('title') {{__("admin.page_title.periodic_search")}} @endsection

@section('contents')
    <div class="row"  id="search-result-list">
        <form id="search_form" method="POST" accept-charset="UTF-8" action="{{route('admin.periodic.search')}}"  style="width: 100%;">
            {{ csrf_field() }}
            {{-- 受注検索条件指定フォーム --}}
            <div class="col-12">
                @include('admin.components.periodic.search_form')
            </div>

            {{-- 検索実行ボタン --}}
            <div class="col-12">
                @include('admin.components.common.search_footer')
            </div>
        </form>

        {{-- 結果表示 --}}
        @if (method_exists($orders, 'total'))
            <div class="col-12">
                @include('admin.components.periodic.search_result')
            </div>
        @endif

        <form id="delete_form" method="POST" accept-charset="UTF-8" action="{{route('admin.periodic.delete')}}">
            {{ csrf_field() }}
        </form>

        <form id="csv_download_form" method="POST" accept-charset="UTF-8" action={{ route('admin.periodic.download_csv') }}>
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
            },

            methods: {
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
                delete_item: function(id) {
                    if (confirm("本当に削除しますか？")) {
                        const data_form = new FormData($("#delete_form")[0]);
                        const form_action = $("#delete_form").attr('action');
                        data_form.set('id', id);
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
                reset_form: function () {
                    app.functions.resetForm('#search_form');
                    $('#page_input').val('');
                    $('#sort_input').val('');
                    $("#number_per_page").val($("#number_per_page option:first").val());
                },
                csv_download: function() {
                    $("form#search_form :input").not(':submit').clone().hide().appendTo("form#csv_download_form");

                    $("#csv_download_form").submit();
                },
            },
        });
    </script>
@endpush