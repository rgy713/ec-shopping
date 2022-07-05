{{-- 顧客詳細画面に表示される受注の一覧テーブル --}}
<div class="card">
    <div class="card-header"><i class="fa fa-shopping-cart"></i>&nbsp;受注履歴 {{count($orders)}}件
        <div class="card-header-actions">
        </div>
    </div>
    <div class="card-body">
        <div class="data-spy" data-spy="scroll" data-offset="65" style="position: relative; height: 300px; overflow: auto; margin-top: .5rem; overflow-y: scroll;">
            <table class="table table-sm">
                <thead>
                <tr>
                    <th>
                        {{__("admin.item_name.order.id")}}<br>
                        {{__("admin.item_name.order.create_date")}}
                    </th>

                    <th>{{__("admin.item_name.product.name")}}</th>
                    <th>{{__("admin.item_name.order.payment_total")}}</th>
                    <th>{{__("admin.item_name.order.payment_method")}}<br>
                        {{__("admin.item_name.order.status")}}

                    </th>
                    <th>
                        {{__("admin.item_name.shipping.shipped_timestamp")}}<br />
                        {{__("admin.item_name.shipping.estimated_arrival_date")}}<br />
                        {{__("admin.item_name.shipping.delivery_slip_num")}}
                    </th>
                    <th></th>
                    <th>購入経路</th>
                </tr>
                </thead>

                <tbody>
                @each('admin.components.order.order_list_of_customer_item', $orders, 'order', 'admin.components.order.order_list_of_customer_empty')
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- 受注状況 start --}}
