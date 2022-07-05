@extends('admin.layouts.main.contents')

@section('title') {{__("admin.page_title.product_search")}} @endsection

@section('contents')
        <div class="row" id="search-result-list">
            <form id="search_form" method="POST" accept-charset="UTF-8" action="{{route('admin.product.search')}}"  style="width: 100%;">
                {{ csrf_field() }}

                {{-- 検索フォーム start --}}
                <div class="col-12">
                    @include('admin.components.product.search_form')
                </div>
                {{-- 検索フォーム end --}}

                <div class="col-12">
                    @include('admin.components.common.search_footer')
                </div>
            </form>

            {{-- 結果表示エリア start --}}
            @if (method_exists($products, 'total'))
                <div class="col-12">
                    @include('admin.components.product.search_result', ['products'=>$products])
                </div>
            @endif
            {{-- 結果表示エリア end --}}

            <form id="delete_form" method="POST" accept-charset="UTF-8" action="{{route('admin.product.delete')}}">
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
                search_results: function(page, sort) {
                    if (page) {
                        $('#page_input').val(page);
                    }
                    if (sort) {
                        $('#sort_input').val(sort);
                    }

                    $('#search_form').submit();
                },
                search_result_new: function() {
                    $('#page_input').val("");
                    $('#sort_input').val("");
                    $('#search_form').submit();
                },
                reset_form: function () {
                    app.functions.resetForm('#search_form');
                    $('#page_input').val('');
                    $('#sort_input').val('');
                    $("#number_per_page").val($("#number_per_page option:first").val());
                }
            }
        });
    </script>
@endpush
