{{-- 202.定期情報変更時の管理者通知用:初期リリース時テンプレートファイル --}}
@inject('prefectureList', 'App\Common\KeyValueLists\PrefectureList')
{{-- 以下本文 --}}
定期ID {{$periodicModifying['id']}} の変更を検知しました。

--------------------------
 定期購入者情報
--------------------------
・郵便番号
{{$periodicModifying['zipcode']}}
{{$periodicModified['zipcode']}}
・都道府県
{{$prefectureList[$periodicModifying['prefecture_id']]}}
{{$prefectureList[$periodicModified['prefecture_id']]}}
・住所1
{{$periodicModifying['address01']}}
{{$periodicModified['address01']}}
・住所2
{{$periodicModifying['address02']}}
{{$periodicModified['address02']}}

--------------------------
 定期間隔
--------------------------
・間隔日数
@if($periodicModifying['interval_days'])
{{$periodicModifying['interval_days']}} 日
@else
-
@endif
@if($periodicModified['interval_days'])
{{$periodicModified['interval_days']}} 日
@else
-
@endif

・間隔月数/指定日付
@if($periodicModifying['interval_month'])
{{$periodicModifying['interval_month']}}ヶ月ごと {{$periodicModifying['interval_date_of_month']}} 日
@else
-
@endif
@if($periodicModified['interval_month'])
{{$periodicModified['interval_month']}}ヶ月ごと {{$periodicModified['interval_date_of_month']}} 日
@else
-
@endif

--------------------------
 定期配送先
--------------------------
・郵便番号
{{$shippingModifying['zipcode']}}
{{$shippingModified['zipcode']}}
・都道府県
{{$prefectureList[$shippingModifying['prefecture_id']]}}
{{$prefectureList[$shippingModified['prefecture_id']]}}
・住所1
{{$shippingModifying['address01']}}
{{$shippingModified['address01']}}
・住所2
{{$shippingModifying['address02']}}
{{$shippingModified['address02']}}
