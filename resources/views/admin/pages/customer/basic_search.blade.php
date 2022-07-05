@extends('admin.layouts.main.contents')

@section('title') {{__('admin.page_title.customer_basic_search')}} @endsection

@section('contents')
    <div class="row" id="customer_search">
        <form id="search_form" method="POST" action="{{route('admin.customer.basic_search_result')}}" accept-charset="UTF-8" autocomplete="off" style="width: 100%;">
            {{ csrf_field() }}

            {{-- 検索条件指定 start --}}
            <div class="col-12">
                @include('admin.components.customer.basic_search_form')
            </div>
            {{-- 検索条件指定 end --}}

            {{-- 検索ボタン start --}}
            <div class="col-12">
                @include('admin.components.common.search_footer')
            </div>
            {{-- 検索ボタン end --}}
        </form>

        {{-- 検索結果表示 start --}}
        @if(isset($customers))
            <div class="col-12">
                @include('admin.components.customer.search_result',['csvButton'=>FALSE])
            </div>
        @endif
        {{-- 検索結果表示 end --}}
        <form id="customer_delete_form" method="POST" action="{{route('admin.customer.delete')}}">
            {{ csrf_field() }}
        </form>
    </div>
@endsection

@push('content_js')
    <script>
        const customerSearchComponent = new Vue({
            el: '#customer_search',

            data: {
                sort: $('#search_form input[name=sort]').val(),
                page: $('#search_form input[name=page]').val(),
            },

            methods: {
                deleteCustomer: function(id) {
                    const form = $("#customer_delete_form")[0];
                    const dataform = new FormData(form);
                    dataform.set('id', id);
                    if(confirm("削除しますか？"))
                        axios.post(form.action, dataform)
                            .then(response => {
                                if (response.data && response.data.status == 'success') {
                                    window.location.reload();
                                }
                            })
                            .catch(error => {
                                console.log(error);
                            });
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
                    $('#page_input').val('');
                    $('#sort_input').val('');
                    $('#search_form input[type=text], input[type=date], textarea').val('');
                    $('#search_form input[type=checkbox]').prop('checked', false);
                }
            }
        });
    </script>
@endpush
