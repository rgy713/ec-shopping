<div class="card">
    <div class="card-header">
        {{__('admin.page_header_name.order_additional_info')}}
        <div class="card-header-actions">
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    {{-- 購入回 --}}
                    <div class="col-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.count1')}}</label>
                            <div class="col-8 col-lg-10">
                                <p class="form-control-plaintext form-control-sm">@if(isset($order) && isset($order['order_count_of_customer'])){{$order['order_count_of_customer']}}@else{{0}}@endif{{__('admin.item_name.common.count_unit')}}</p>
                            </div>
                        </div>
                    </div>
                    {{-- 購入回 --}}
                    <div class="col-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.count2')}}</label>
                            <div class="col-8 col-lg-10">
                                <p class="form-control-plaintext form-control-sm">@if(isset($order) && isset($order['order_count_of_customer_without_cancel'])){{$order['order_count_of_customer_without_cancel']}}@else{{0}}@endif{{__('admin.item_name.common.count_unit')}}</p>
                            </div>
                        </div>
                    </div>

                    @if(isset($order) && isset($order['periodic_order_id']))
                        {{-- 定期ID --}}
                        <div class="col-6">
                            <div class="row form-group">
                                <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.id')}}</label>
                                <div class="col-8 col-lg-10">
                                    <p class="form-control-plaintext form-control-sm">@if(isset($order) && isset($order['periodic_order_id'])){{$order['periodic_order_id']}}@endif</p>
                                </div>
                            </div>
                        </div>
                        {{-- 定期回 --}}
                        <div class="col-6">
                            <div class="row form-group">
                                <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.count')}}</label>
                                <div class="col-8 col-lg-10">
                                    <p class="form-control-plaintext form-control-sm">@if(isset($order) && isset($order['periodic_count'])){{$order['periodic_count']}}{{__('admin.item_name.common.count_unit')}}@endif</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- 購入時警告 --}}
                    <div class="col-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.purchase_warnings')}}</label>
                            <div class="col-8 col-lg-10">
                                <p class="form-control-plaintext form-control-sm">@if(isset($order) && isset($order['purchase_warning_flag']) && $order['purchase_warning_flag']){{__('admin.item_name.common.exist')}}@endif</p>
                            </div>
                        </div>
                    </div>
                    {{-- 購入者重複疑い --}}
                    <div class="col-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.pair_relationships')}}</label>
                            <div class="col-8 col-lg-10">
                                <input name="customer_pair_relationships" type="hidden" value="@if(isset($order) && isset($order['customer_pair_relationships'])){{$order['customer_pair_relationships']}}@endif">
                                <p class="form-control-plaintext form-control-sm">@if(isset($order) && isset($order['customer_pair_relationships'])){{$order['customer_pair_relationships']}}@endif</p>
                            </div>
                        </div>
                    </div>

                    {{-- 同梱 --}}
                    <div class="col-6">
                        <div class="row form-group">
                            <label class="col-4 col-lg-2 col-form-label col-form-label-sm">{{__('admin.item_name.order.bundle_shippings')}}</label>
                            <div class="col-8 col-lg-10">
                                <input name="customer_pair_relationships" type="hidden" value="@if(isset($order) && isset($order['order_bundle_shippings'])){{$order['order_bundle_shippings']}}@endif">
                                <p class="form-control-plaintext form-control-sm">@if(isset($order) && isset($order['order_bundle_shippings'])){{$order['order_bundle_shippings']}}@endif</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>