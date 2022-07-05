{{-- 4.配送に関するお知らせ:初期リリース時テンプレートファイル --}}
@inject('prefectureList', 'App\Common\KeyValueLists\PrefectureList')
{{-- 以下本文 --}}

この度は、数ある製品の中より弊社製品をご注文頂きまして、
誠にありがとうございます。

お届け予定日とご注文内容は以下の通りです。ご確認ください。

******************************************************************************************
お届け予定
発送予定日：@if(isset($order->shipping->requested_delivery_date)){{$order->shipping->scheduled_shipping_date->format('Y-m-d')}}@endif

到着予定日：@if(isset($order->shipping->estimated_arrival_date)){{$order->shipping->estimated_arrival_date->format('Y-m-d')}}@endif

お届け希望日時：@if(isset($order->shipping->requested_delivery_date)){{$order->shipping->requested_delivery_date->format('Y-m-d')}}@else 指定なし @endif {{$order->shipping->delivery_time_name or ""}}
※交通事情等により予定の日時に届かない場合があります。


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ご注文内容
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
[ご注文日時]{{$order->created_at->format('Y-m-d H:i')}}
[受付番号]{{$order->id}}
@if($order->periodicOrder)
[定期間隔]@if($order->periodicOrder->periodic_interval_type_id===1){{$order->periodicOrder->interval_days}}日間隔@elseif($order->periodicOrder->periodic_interval_type_id===2){{$order->periodicOrder->interval_month}}ヶ月毎 {{$order->periodicOrder->interval_date_of_month}}日@endif
@endif
[製品名]
@foreach($order->details as $detail)
{{$detail->product_name}} {{($detail->price + $detail->tax)}}円　×　{{$detail->quantity}}
@endforeach
[お支払方法]{{$order->payment_method_name}}

[小　計] {{$order->subtotal}} 円
[送　料] {{$order->delivery_fee}} 円
@if($order->payment_method_fee > 0)
[手数料] {{$order->payment_method_fee}} 円
@endif
@if($order->discount > 0)
[値引き] -{{$order->discount}} 円
@endif
===============
[お支払合計] {{$order->payment_total}} 円
[消費税] {{$order->payment_total_tax}} 円

[備考欄]{{$order->message_from_customer}}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■お届け先
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
お名前　：{{$order->shipping->name01}} {{$order->shipping->name02}}　様
フリガナ：{{$order->shipping->kana01}} {{$order->shipping->kana02}}　サマ
郵便番号：〒{{$order->shipping->zipcode}}
住　　所：{{$prefectureList[$order->shipping->prefecture_id]}} {{$order->shipping->address01}} {{$order->shipping->address02}}
電話番号：{{$order->shipping->phone_number01}}
配達ご希望日：@if(isset($order->shipping->requested_delivery_date)){{$order->shipping->requested_delivery_date->format('Y-m-d')}}@else 指定なし @endif {{$order->shipping->delivery_time_name or ""}}
@if($order->shipping->delivery_slip_num)
伝票番号：{{$order->shipping->delivery_slip_num}}
@endif



******************************************************************************************

※3営業日以上経っても「配送に関するお知らせ」メールが届かない場合は、
何らかの不具合が生じている可能性がございます。
その際はお手数ですが、下記フリーダイヤルまでご連絡ください。

フリーダイヤル:0120-50-2000[携帯OK]
9:30~18:00(土日祝・年末年始・GW・お盆を除く)
