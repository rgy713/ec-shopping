<?php


namespace App\Services;

use App\Environments\Develop\Payment\Paygent\PaygentStubService;
use App\Models\Customer;
use App\Models\CustomerAttachments;
use App\Models\Order;
use App\Models\PeriodicOrder;
use App\Models\ShopMemo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Environments\Interfaces\Paygent as PaygentInterface;

class CustomerInfoService
{

    public function getCustomer($id)
    {
        $customer = app(Customer::class)->find($id);
        return $customer;
    }

    public function periodicOrderIntervalUpdate($params)
    {
        $order = app(PeriodicOrder::class)->find($params["periodic_order_id"]);
        $order->periodic_interval_type_id = $params["periodic_interval_type_id"];
        if($order->periodic_interval_type_id == 1)
        {
            $order->interval_days = $params["interval_days"];
        }
        elseif ($order->periodic_interval_type_id == 2)
        {
            $order->interval_month = $params["interval_month"];
            $order->interval_date_of_month = $params["interval_date_of_month"];
        }
        $order->save();

        $shop_memo = new ShopMemo();
        $shop_memo->note = $params["shop_memo_note"];
        $shop_memo->customer_id = $order->customer_id;
        $shop_memo->periodic_order_id = $order->id;
        $shop_memo->created_by = Auth::user()->id;
        $shop_memo->save();
    }

    public function periodicOrderNextUpdate($params)
    {
        $order = app(PeriodicOrder::class)->find($params["periodic_order_id"]);
        $order->next_delivery_date = $params["next_delivery_date"];
        $order->save();

        $shop_memo = new ShopMemo();
        $shop_memo->note = $params["shop_memo_note"];
        $shop_memo->customer_id = $order->customer_id;
        $shop_memo->periodic_order_id = $order->id;
        $shop_memo->created_by = Auth::user()->id;
        $shop_memo->save();
    }

    public function periodicOrderFailUpdate($params)
    {
        $order = app(PeriodicOrder::class)->find($params["periodic_order_id"]);
        $order->failed_flag = $params["failed_flag"] ? FALSE : TRUE;
        $order->save();

        $shop_memo = new ShopMemo();
        $shop_memo->note = $params["shop_memo_note"];
        $shop_memo->customer_id = $order->customer_id;
        $shop_memo->periodic_order_id = $order->id;
        $shop_memo->created_by = Auth::user()->id;
        $shop_memo->save();
    }

    public function periodicOrderStopUpdate($params)
    {
        $order = app(PeriodicOrder::class)->find($params["periodic_order_id"]);
        $order->stop_flag = $params["stop_flag"] ? FALSE : TRUE;
        $order->save();

        $shop_memo = new ShopMemo();
        $shop_memo->note = $params["shop_memo_note"];
        $shop_memo->customer_id = $order->customer_id;
        $shop_memo->periodic_order_id = $order->id;
        $shop_memo->created_by = Auth::user()->id;
        $shop_memo->save();
    }

    public function periodicOrderPaymentUpdate($params)
    {
        $order = app(PeriodicOrder::class)->find($params["periodic_order_id"]);
        $order->payment_method_id = $params["payment_method_id"];
        if(isset($params["settlement_card_id"]))
            $order->settlement_card_id = $params["settlement_card_id"];
        $order->save();

        $shop_memo = new ShopMemo();
        $shop_memo->note = $params["shop_memo_note"];
        $shop_memo->customer_id = $order->customer_id;
        $shop_memo->periodic_order_id = $order->id;
        $shop_memo->created_by = Auth::user()->id;
        $shop_memo->save();
    }

    public function getSettlementCards($customer_id)
    {
        /** @var PaygentInterface $paygentService */
        $paygentService = app(PaygentInterface::class);

        $response = $paygentService->sendTelegramCreditStockGet($customer_id);
        $data = [];
        foreach ($response as $item) {
            $data[]=[
                "settlement_card_id"=>$item["customer_card_id"],
                "card_number"=>str_pad("", strlen($item["card_number"]) - 4, '*', STR_PAD_LEFT).substr($item["card_number"],-4),
                "card_valid_term"=>$item["card_valid_term"],
                "cardholder_name"=>$item["cardholder_name"],
            ];
        }
        return $data;
    }

    public function attachmentUpload($params)
    {
        $attachment = new CustomerAttachments();
        $attachment->customer_id = $params["customer_id"];
        $attachment->title = $params["file_title"];
        $file_name = md5($attachment->customer_id.$attachment->title.Carbon::now()->timestamp);
        $extension = $params['file_content']->getClientOriginalExtension();
        $file_path = "customers/attachments/" . $file_name.".".$extension;
        $params['file_content']->storeAs(
            'public', $file_path
        );
        $attachment->file_path = $file_path;
        $attachment->save();
    }

    public function attachmentDelete($id)
    {
        $attachment = app(CustomerAttachments::class)->find($id);
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();
    }
}