{{-- modal:商品選択モーダル --}}
<div id="modal_order_item_search_form" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__("admin.item_name.order.select_product")}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('admin.components.product.modal_search_form')

                {{-- 商品情報 --}}
                <div class="row">
                    <div class="col-12" v-if="show_results">
                        @include('admin.components.product.modal_search_result')
                    </div>
                </div>

            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__("admin.operation.close")}}</button>
            </div>
        </div>
    </div>
</div>

