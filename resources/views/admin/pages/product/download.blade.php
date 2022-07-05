@extends('admin.layouts.main.contents')

@section('title') 商品管理 > CSVダウンロード @endsection


@section('contents')
    <div class="row">
        <div class="col-12">
            @include("admin.components.product.download1")
        </div>

        <div class="col-12">
            @include("admin.components.product.download2")
        </div>

    </div>
@endsection

@push('content_js')
    <script>
        function downloadCSV(action, type){
            axios.post(action, {
                "type": type
            })
                .then( response => {
                    if(response.data.status != "error"){

                    }
                })
                .catch((error) => {

                });
        }
    </script>
@endpush

