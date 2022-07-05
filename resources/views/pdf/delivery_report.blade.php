@inject('prefectureList', 'App\Common\KeyValueLists\PrefectureList')
@inject('paymentList', 'App\Common\KeyValueLists\PaymentList')
@inject('deliveryList', 'App\Common\KeyValueLists\DeliveryList')
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @font-face {
            font-family: ipag;
            font-style: normal;
            font-weight: normal;
            src: url('{{ storage_path('fonts/ipag.ttf') }}') format('truetype');
        }

        body {
            font-family: ipag !important;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            font-size: 11px;
            font-weight: 200;
            line-height: 16px;
            color: #686868;
            text-align: left;
            background-color: #fff;
        }

        @page {
            size: 595pt 842pt;
            margin: 30pt 56pt;
        }
        .page-break {
            page-break-after: always;
        }
        .row {
            width: 100%;
            position: relative;
        }
        .text-center{
            text-align: center;
        }
        .right{
            text-align: right;
        }
        table{
            border-collapse: collapse;
        }
        tr{
            width: 100%;
        }
        .border{
            border: 1px solid #686868;
            padding: 5px;
        }
        .bottom{
            height: 70px;
            width: 14.28%;
            vertical-align: top;
            border: 1px solid #686868;
            padding: 3px;
        }
    </style>
</head>
<body>
@if(isset($orders))
    @foreach($orders as $order)
<div class="row page-break">
    <div class="row" style="text-align: center;margin: 20px; font-size: 16px;">お買上げ明細書</div>
    <table class="row" style="table-layout: fixed;">
        <tbody>
        <tr>
            <td>
                <div>〒{{$order->shipping->zipcode}}</div>
                <div>{{$prefectureList[$order->shipping->prefecture_id]}}{{$order->shipping->address01}}</div>
                <div>{{$order->shipping->address02}}</div>
                <div>{{$order->shipping->name01}} {{$order->shipping->name02}}　様</div>
            </td>
            <td style="text-align: right;">
                <div>ご注文日：{{$order->created_at->format('Y-m-d H:i')}}</div>
                <div>ご注文番号：{{$order->id}}</div>
                <div>{{$paymentList[$order->payment_method_id]}}</div>
                <div>{{$deliveryList[$order->delivery_id]}}</div>
            </td>
        </tr>
        </tbody>
    </table>
    <table class="row" style="table-layout: fixed;margin-top: 40px;border-top:2px solid #545454;border-bottom:2px solid #545454;">
        <tr>
            <td style="height:30px;width: 64%;padding-left: 20px;">商品名</td>
            <td style="height:30px;width: 12%;">単価</td>
            <td style="height:30px;width: 12%;">数量</td>
            <td style="height:30px;width: 12%;">金額</td>
        </tr>
    </table>
    @foreach($order->details as $one)
        <table class="row" style="table-layout: fixed;border-bottom:1px solid #686868;">
        <tr>
            <td style="height:40px;width: 64%;padding-left: 20px;">{{$one->product_name}}</td>
            <td style="height:40px;width: 12%;">{{$one->price}}</td>
            <td style="height:40px;width: 12%;">{{$one->quantity}}</td>
            <td style="height:40px;width: 12%;">{{$one->price * $one->quantity}}</td>
        </tr>
        </table>
    @endforeach
    <table class="row" style="table-layout: fixed;border-bottom:2px solid #545454;">
        <tr>
            <td style="width: 64%;padding-left: 20px;"></td>
            <td style="width: 12%;"></td>
            <td style="width: 12%;">
               <div>小計</div>
               <div>消費税</div>
               <div>送料</div>
               <div>手数料</div>
               <div>値引き</div>
            </td>
            <td style="width: 12%;padding: 10px 0;">
                <div>{{$order->subtotal}}</div>
                <div>{{$order->subtotal_tax}}</div>
                <div>{{$order->delivery_fee}}</div>
                <div>{{$order->payment_method_fee}}</div>
                <div>{{$order->discount}}</div>
            </td>
        </tr>
    </table>
    <table class="row" style="table-layout: fixed;">
        <tr>
            <td style="width: 64%;padding-left: 20px;"></td>
            <td style="width: 12%;"></td>
            <td style="width: 12%;">合計</td>
            <td style="width: 12%;">{{$order->payment_total}}</td>
        </tr>
    </table>
    <div class="row" style="position: absolute; bottom: 0;">
        <table class="row" style="margin-top: 20px;">
            <tr>
                <td style="width: 64%;">
                    <div class="row text-center" style="border: 1px solid #686868;">
                        お届け致しました商品のお問い合わせは下記までご連絡ください
                    </div>
                    <div class="row text-center" style="border: 1px solid #686868;border-top: none; padding:10px 0;">
                        メディカルコート株式会社 お客様サポートデスク　=>　0120-50-2000
                    </div>
                </td>
                <td style="width: 36%;">
                    <div class="row text-center">
                        <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($order->id, 'C39')}}" alt="barcode" />
                    </div>
                    <div class="row text-center">*{{$order->id}}*</div>
                </td>
            </tr>
        </table>
        <table class="row" style="line-height: 1em; margin-top: 20px;">
            <tr>
                <td style="width: 64%;">
                    <div class="row">
                        商品の返品 ・交換について
                    </div>
                    <div class="row" style="border: 1px solid #686868;padding:15px 10px;font-size: 9px;">
                        ●お客様のご都合による返品(未開封に限る)<br/>
                        　返品は商品到着後、8日以内までお受けいたします。<br/>
                        　配送料・代引手数料はお客様のご負担となります。ご了承ください。<br/>
                        <br/>
                        ●弊社不備による交換(不良品など)<br/>
                        　商品ご到着後、8日以内に着払いにてご返送ください。<br/>
                        　弊社より代替品をお送りさせて頂きます。<br/>
                        　交換は不良品以外は承っておりません。<br/>
                        <br/>
                        ●返品・交換をお受けできない商品<br/>
                        　※商品がお手元に届いてから8日以上を経過した商品<br/>
                        　※店舗にてお買い上げ頂いた商品<br/>
                        　※弊社の不備なく汚損または破損された商品<br/>
                    </div>
                </td>
                <td style="width: 2%;"></td>
                <td style="width: 34%;">
                    <div class="row">
                        全額返金保証について
                    </div>
                    <div class="row" style="border: 1px solid #686868;padding:10px;font-size: 9px;">
                        100％返金保証付き商品をご購入された方へ<br/>
                        <br/>
                        商品をお受け取り後しっかりとご使用ください。<br/>
                        その結果「不満足」と判断された場合は、<br/>
                        商品代金を全額返金致します。<br/>
                        <br/>
                        ご返金（全額返金）をご希望の際には、<br/>
                        下記の宛先にお電話にてご連絡ください。<br/>
                        0120-50-2000<br/>
                        <br/>
                        上記連絡先にご連絡を頂きましたのち、<br/>
                        ご購入頂きました商品の容器と外箱を<br/>
                        ご返送ください。<br/>
                        （送料のみご負担ください。）
                    </div>
                </td>
            </tr>
        </table>
        <div class="row right" style="margin-top: 20px;">
            メディカルコート株式会社 お客様サポートデスク<br/>
            作成日:{{date('Y.m.d')}}
        </div>
    </div>
</div>
<div class="row @if(!$loop->last)page-break @endif">
    <table class="row">
        <tr>
            <td class="border" style="width: 25%;">注文No</td>
            <td class="border" style="width: 75%;">{{$order->id}}</td>
        </tr>
        <tr>
            <td class="border" style="width: 25%;">注文日時</td>
            <td class="border" style="width: 75%;">{{$order->created_at->format('Y.m.d H:i')}}</td>
        </tr>
    </table>
    <table class="row">
        <tr>
            <td class="border" style="width: 64%;">注文商品</td>
            <td class="border right" style="width: 12%;">注文数</td>
            <td class="border right" style="width: 12%;">単価</td>
            <td class="border right" style="width: 12%;">小計</td>
        </tr>
        @foreach($order->details as $one)
        <tr>
            <td class="border" style="width: 64%;">{{$one->product_name}}</td>
            <td class="border right" style="width: 12%;">{{$one->quantity}}</td>
            <td class="border right" style="width: 12%;">{{$one->price}}</td>
            <td class="border right" style="width: 12%;">{{$one->price * $one->quantity}}</td>
        </tr>
        @endforeach
    </table>
    <table class="row">
        <tr>
            <td class="border" style="width: 88%;">小計</td>
            <td class="border right" style="width: 12%;">{{$order->subtotal}}</td>
        </tr>
        <tr>
            <td class="border" style="width: 88%;">消費税</td>
            <td class="border right" style="width: 12%;">{{$order->payment_total_tax}}</td>
        </tr>
        <tr>
            <td class="border" style="width: 88%;">送料</td>
            <td class="border right" style="width: 12%;">{{$order->delivery_fee}}</td>
        </tr>
        <tr>
            <td class="border" style="width: 88%;">手数料</td>
            <td class="border right" style="width: 12%;">{{$order->payment_method_fee}}</td>
        </tr>
        <tr>
            <td class="border" style="width: 88%;">値引き</td>
            <td class="border right" style="width: 12%;">{{$order->discount}}</td>
        </tr>
        <tr>
            <td class="border" style="width: 88%;">合計</td>
            <td class="border right" style="width: 12%;">{{$order->payment_total}}</td>
        </tr>
        <tr>
            <td class="border" style="width: 88%;">今回ご使用ポイント</td>
            <td class="border right" style="width: 12%;">0pt</td>
        </tr>
        <tr>
            <td class="border" style="width: 88%;">今回付与ポイント</td>
            <td class="border right" style="width: 12%;">0pt</td>
        </tr>
        <tr>
            <td class="border" style="width: 88%;">お届け希望日付</td>
            <td class="border right" style="width: 12%;">@if(isset($order->shipping->requested_delivery_date)){{$order->shipping->requested_delivery_date}}@else 指定なし @endif</td>
        </tr>
        <tr>
            <td class="border" style="width: 88%;">お届け時間帯</td>
            <td class="border right" style="width: 12%;">@if(isset($order->shipping->delivery_time_name)){{$order->shipping->delivery_time_name}}@else 指定なし @endif</td>
        </tr>
        <tr>
            <td class="border" style="width: 88%;">支払方法 </td>
            <td class="border right" style="width: 12%;">{{$paymentList[$order->payment_method_id]}}</td>
        </tr>
    </table>
    <table class="row">
        <tr>
            <td class="border" style="width: 25%;">会社名 </td>
            <td class="border" style="width: 75%;"></td>
        </tr>
        <tr>
            <td class="border" style="width: 25%;">ご注文者氏名</td>
            <td class="border" style="width: 75%;">{{$order->name01}}{{$order->name02}}</td>
        </tr>
        <tr>
            <td class="border" style="width: 25%;">ご注文者フリガナ</td>
            <td class="border" style="width: 75%;">{{$order->kana01}}{{$order->kana02}}</td>
        </tr>
        <tr>
            <td class="border" style="width: 25%;">ご注文者電話番号</td>
            <td class="border" style="width: 75%;">{{$order->phone_number01}}, {{$order->phone_number02}}</td>
        </tr>
        <tr>
            <td class="border" style="width: 25%;">ご注文者郵便番号 </td>
            <td class="border" style="width: 75%;">{{$order->zipcode}}</td>
        </tr>
        <tr>
            <td class="border" style="width: 25%;">ご注文者住所1 </td>
            <td class="border" style="width: 75%;">{{$prefectureList[$order->prefecture_id]}} {{$order->address01}}</td>
        </tr>
        <tr>
            <td class="border" style="width: 25%;">ご注文者住所2 </td>
            <td class="border" style="width: 75%;">{{$order->shipping->address02}}</td>
        </tr>
        <tr>
            <td class="border" style="width: 25%;">お届け先氏名</td>
            <td class="border" style="width: 75%;">{{$order->shipping->name01}}{{$order->shipping->name02}}</td>
        </tr>
        <tr>
            <td class="border" style="width: 25%;">お届け先フリガナ</td>
            <td class="border" style="width: 75%;">{{$order->shipping->kana01}}{{$order->shipping->kana02}}</td>
        </tr>
        <tr>
            <td class="border" style="width: 25%;">お届け先郵便番号</td>
            <td class="border" style="width: 75%;">{{$order->shipping->zipcode}}</td>
        </tr>
        <tr>
            <td class="border" style="width: 25%;">お届け先住所1</td>
            <td class="border" style="width: 75%;">{{$prefectureList[$order->shipping->prefecture_id]}}{{$order->shipping->address01}}</td>
        </tr>
        <tr>
            <td class="border" style="width: 25%;">お届け先住所2</td>
            <td class="border" style="width: 75%;">{{$order->shipping->address02}}</td>
        </tr>
        <tr>
            <td class="border" style="width: 25%;">贈答品</td>
            <td class="border" style="width: 75%;"></td>
        </tr>
        <tr>
            <td class="border" style="width: 25%;">備考</td>
            <td class="border" style="width: 75%;">{{$order->message_from_customer}}</td>
        </tr>
    </table>
    <table class="row" style="font-size: 8px;position: absolute; bottom: 70px;">
        <tr>
            <td class="bottom">①入金確認 </td>
            <td class="bottom">②入金確認メール</td>
            <td class="bottom">③レジ処理FAX</td>
            <td class="bottom">④発注依頼</td>
            <td class="bottom">⑤発注</td>
            <td class="bottom">⑥梱包</td>
            <td class="bottom">⑦ヤマト番号発送メール</td>
        </tr>
    </table>
</div>
    @endforeach
@endif
</body></html>
