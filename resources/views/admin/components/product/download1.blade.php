<div class="card">
    {{-- header start --}}
    <div class="card-header">
        {{__('admin.page_header_name.product_download1')}}
        <div class="card-header-actions">
        </div>
    </div>

    {{-- body start --}}
    <div class="card-body ">
        <div class="row">
            <div class="col-3">
                {{__('admin.item_name.product.product_purchase_warnings_type1_csv')}}
            </div>

            <div class="col-3">
                <a class="btn btn-sm btn-primary" href="{{route("admin.product.download_csv", ['type'=>10])}}"><i class="fa fa-download"></i> &nbsp;{{__('admin.item_name.product.download')}}</a>
            </div>

            <div class="col-3">
                {{__('admin.item_name.product.product_purchase_warnings_type2_csv')}}
            </div>
            <div class="col-3">
                <a class="btn btn-sm btn-primary" href="{{route("admin.product.download_csv", ['type'=>11])}}"><i class="fa fa-download"></i> &nbsp;{{__('admin.item_name.product.download')}}</a>
            </div>
        </div>

    </div>

</div>