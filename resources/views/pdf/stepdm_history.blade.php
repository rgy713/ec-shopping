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
            font-size: 12px;
            font-weight: 200;
            line-height: 16px;
            color: #686868;
            text-align: left;
            background-color: #fff;
        }

        @page {
            size: 595pt 842pt;
            margin: 0 0;
        }

        .row {
            width: 100%;
            position: relative;
        }

        .col-4 {
            width: 33.333%;
            padding: 15px;
            position: relative;
        }

        .content {
            width: 100%;
            position: relative;
            word-break: break-all;
            text-justify: inter-word;
        }

        .right {
            text-align: right;
        }
    </style>
</head>
<body>
<table class="row" style="table-layout: fixed;">
    <tbody>
        <tr>
        @php($i=0)
        @foreach($data as $one)
            @php($i++)
            <td class="col-4">
                <div class="content">〒{{$one->order_zipcode01}}-{{$one->order_zipcode02}}</div>
                <div class="content">{{textWrapping($one->prefecture_name.' '.$one->order_address01, 20)}}</div>
                <div class="content">{{textWrapping($one->order_address02_1.$one->order_address02_2, 20)}}</div>
                <div class="content">{{$one->order_name01}} {{$one->order_name02}} 様</div>
                <div class="content right">{{$one->stepdm_setting_code}}</div>
            </td>
            @if($i==3)
                @php($i=0)
                </tr>
                <tr>
            @endif
        @endforeach
        </tr>
    </tbody>
</table>
</body>
</html>