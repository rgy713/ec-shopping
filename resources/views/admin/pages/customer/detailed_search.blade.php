@extends('admin.layouts.main.contents')
@section('title') {{__('admin.page_title.customer_detail_search')}} @endsection

@section('contents')

    <div class="row" id="customer_detail_search">
        <form id="search_form" method="POST" action="{{route('admin.customer.detailed_search_result')}}" accept-charset="UTF-8" autocomplete="off">
            {{ csrf_field() }}

            <div class="col-12">
                @include('admin.components.customer.detailed_search_form')
            </div>

            <div class="col-12">
                @include('admin.components.common.search_footer')
            </div>
        </form>
        @if(isset($customers))
            <div class="col-12">
                @include('admin.components.customer.search_result',['csvButton'=>true])
            </div>
        @endif
        <form id="customer_delete_form" method="POST" action="{{route('admin.customer.delete')}}">
            {{ csrf_field() }}
        </form>
        <form id="download_csv_form" method="POST" action="{{route('admin.customer.detail.download.csv')}}">
        </form>
    </div>
@endsection

@push('content_js')
    <script>
        const customerDetailSearchComponent = new Vue({
            el: '#customer_detail_search',

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
                    $('#search_form input[type=text], input[type=number], input[type=date], textarea, select:not(#number_per_page)').val('');
                    $('#search_form input[type=checkbox]').prop('checked', false);
                    $('#search_form input').removeClass('is-invalid');
                },
                only_number:function(evt){
                    if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57)
                    {
                        evt.preventDefault();
                    }
                },
                min_max_date(evt, name, type){
                    const date = evt.target.value;
                    if(name){
                        if(type == 0){
                            $("#customer_detail_search input[name=" + name + "]").attr("min", date);
                        }else if(type == 1){
                            $("#customer_detail_search input[name=" + name + "]").attr("max", date);
                        }
                    }
                }
            }
        });

        function customerCsvDownload() {
            const $csvDownloadForm = $("form#download_csv_form");
            $csvDownloadForm.html("");
            $("form#search_form :input").not(':submit').clone().hide().appendTo("form#download_csv_form");
            $csvDownloadForm.submit();
        }
    </script>
@endpush
