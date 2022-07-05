@inject('periodicOrderStatusList', 'App\Common\KeyValueLists\PeriodicOrderStatusList')
@inject('periodicOrderStopFlagList', 'App\Common\KeyValueLists\PeriodicOrderStopFlagList')
@inject('periodicStopReasonList', 'App\Common\KeyValueLists\PeriodicStopReasonList')
@inject('paymentList', 'App\Common\KeyValueLists\PaymentList')

{{-- 顧客詳細画面に表示される受注の一覧テーブル --}}
{{-- 定期状況 start --}}
<div class="card">
    <div class="card-header"><i class="fa fa-refresh"></i>&nbsp;定期状況 {{count($periodicOrders)}}件
        <div class="card-header-actions">
        </div>
    </div>
    <div class="card-body">
        <div class="data-spy" data-spy="scroll" data-offset="65" style="position: relative; height: 240px; overflow: auto; margin-top: .5rem; overflow-y: scroll;">

            <form class="form-inline" action="" method="post" enctype="multipart/form-data">
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th>
                            {{__("admin.item_name.periodic.id")}}<br>
                            {{__("admin.item_name.periodic.count")}}
                        </th>

                        <th>{{__("admin.item_name.product.name")}}</th>

                        <th>
                            合計金額（前回）
                        </th>

                        <th>
                            {{__("admin.item_name.order.payment_method")}}<br>
                            {{__("admin.item_name.periodic.interval")}}<br />
                        </th>

                        <th>
                            {{__("admin.item_name.periodic.prev_create_date")}}<br />
                            {{__("admin.item_name.periodic.next_create_date")}}
                        </th>

                        <th>{{__("admin.item_name.periodic.status")}}<br>
                            {{__("admin.item_name.periodic.stop_flag")}}
                        </th>

                    </tr>
                    </thead>

                    <tbody>
                    @each('admin.components.periodic.order_list_of_customer_item', $periodicOrders, 'periodicOrder', 'admin.components.periodic.order_list_of_customer_empty')
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

{{-- modal:定期間隔変更 --}}
@include('admin.components.periodic.modal_periodic_interval_form')

{{-- modal2:次回到着 --}}
@include('admin.components.periodic.modal_periodic_next_delivery_date_form')

{{-- modal3:失敗フラグ --}}
@include('admin.components.periodic.modal_periodic_failed_flag_form')

{{-- modal4:停止フラグ --}}
@include('admin.components.periodic.modal_periodic_stop_flag_form')

{{-- modal5:支払い方法変更 --}}
@include('admin.components.periodic.modal_periodic_payment_form')


{{-- 定期状況 end --}}
