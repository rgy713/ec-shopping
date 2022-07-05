{{-- 201.住所変更時の管理者通知用:初期リリース時テンプレートファイル --}}
@inject('prefectureList', 'App\Common\KeyValueLists\PrefectureList')

顧客ID {{$modifying['id']}} の住所情報の変更を検知しました。

・郵便番号
 {{$modifying['zipcode']}} → {{$modified['zipcode']}}
・都道府県
 {{$prefectureList[$modifying['prefecture_id']]}} → {{$prefectureList[$modified['prefecture_id']]}}
・住所1
 {{$modifying['address01']}} → {{$modified['address01']}}
・住所2
 {{$modifying['address02']}} → {{$modified['address02']}}
