@extends('admin.layouts.main.contents')

@section('title') {{__("admin.page_title.media_search")}} @endsection

@section('contents')
    <div class="row" id="search-result-list">
        <form id="search_form" method="POST" accept-charset="UTF-8" action="{{route('admin.media.search')}}"  style="width: 100%;">
            {{ csrf_field() }}

            {{-- 検索フォーム start --}}
            <div class="col-12">
                @include('admin.components.media.search_form')
            </div>
            {{-- 検索フォーム end --}}

            <div class="col-12">
                @include('admin.components.common.search_footer')
            </div>
        </form>

        {{-- 結果表示エリア start --}}
        @if (method_exists($medias, 'total'))
            <div class="col-12">
                @include('admin.components.media.search_result')
            </div>
        @endif
        {{-- 結果表示エリア end --}}

        <form id="delete_form" method="POST" accept-charset="UTF-8" action="{{route('admin.media.delete')}}">
            {{ csrf_field() }}
        </form>

        <form id="csv_download_form" method="POST" accept-charset="UTF-8" action={{ route('admin.media.download_csv') }}>
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
               csv_download: function() {
                   $("form#search_form :input").not(':submit').clone().hide().appendTo("form#csv_download_form");

                   $("#csv_download_form").submit();
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