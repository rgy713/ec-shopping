<div class="card">
    {{-- header start --}}
    <div class="card-header">
        {{__('admin.page_header_name.product_download2')}}
        <div class="card-header-actions">
        </div>
    </div>

    {{-- body start --}}
    <div class="card-body ">
        <div class="row">
            <div class="col-3">
                <a class="btn btn-sm btn-primary"  href="{{route("admin.product.download_csv", ['type'=>12])}}"><i class="fa fa-download"></i> &nbsp;{{__('admin.item_name.product.download')}}</a>
            </div>
        </div>
    </div>

</div>