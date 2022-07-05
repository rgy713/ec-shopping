{{-- 1.ご注文内容のご確認(自動返信メール):初期リリース時テンプレートファイル --}}
@inject('prefectureList', 'App\Common\KeyValueLists\PrefectureList')
{{-- 以下本文 --}}

{{$order->name01}}{{$order->name02}} 様


この度は、弊社製品をお買い上げ頂き、誠にありがとうございます。
お客様のご注文を下記の内容で承りましたのでご連絡致します。

※このメールは自動返信メールにてお送りしております。
　本メールへご返信頂きましてもご回答できませんのでご了承くださいませ。

※発送日・お届け日を確認し、発送前に改めて
　「配送に関するお知らせ」のメールをお送りさせていただきます。

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ご注文内容
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
[ご注文日時]{{$order->created_at->format('Y-m-d H:i')}}

[受付番号]{{$order->id}}
@if($order->periodicOrder)
[定期間隔]@if($order->periodicOrder->periodic_interval_type_id===1){{$order->periodicOrder->interval_days}}日間隔@elseif($order->periodicOrder->periodic_interval_type_id===2){{$order->periodicOrder->interval_month}}ヶ月毎 {{$order->periodicOrder->interval_date_of_month}}日
@endif
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


このたびはご注文有難うございました。

製品の到着まで今しばらくお待ちください。
