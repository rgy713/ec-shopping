## メール送信テスト(メールサーバ設定確認)
```
php artisan tinker
Mail::raw('test mail',function($message) {$message->to('hoge@hoge.com')->subject('test');});
```

# コード雛形生成
## イベントとイベントリスナの作成
EventServiceProviderに書いた組み合わせが自動で生成される  
（既に存在しているファイルは無視される）
```
php artisan event:generate
```

※作成したイベントリスナは、キューに入れる場合はShouldQueueインターフェースをimplement
※ShouldQueueインターフェースをimplementした場合は、FailedWithQueueトレイトをuse
（Job失敗時のイベントを発生させる処理を共通化してある）

# キャッシュ制御
## viewキャッシュのクリア
Blade拡張した場合などは、一度キャッシュを切らないと変更が反映されない。
```
php artisan view:clear
```

## ファイルキャッシュのクリア
現状、開発サーバーではfileキャッシュを使っている  
（Redisに差し替えた場合に、このコマンドでキャッシュが消えるのかは未確認）
※現時点では、管理画面のシステム情報を表示する箇所でキャッシュを試しに使っている。
```
php artisan cache:clear
```

# データベース
## 開発環境での接続
```
sudo -u postgres psql -d homestead
```

## マイグレーションやり直し
面倒なので、大体このコマンドで済むようにしている。
※Seederは環境によって差し替わるようにしているので、開発環境専用のSeederは
app\Environments\Develop　以下に入れてください。
```
php artisan migrate:refresh --seed
``` 

## 通知の作成

```
php artisan make:notification Batch\\AggregatePortfolioFinishedNotification
php artisan make:notification Batch\\AutoMergeFinishedNotification
php artisan make:notification Batch\\BundleShippingFinishedNotification
php artisan make:notification Batch\\CheckOrderDataFinishedNotification
php artisan make:notification Batch\\CreateAspMediaCodeFinishedNotification
php artisan make:notification Batch\\PeriodicOrderFinishedNotification
php artisan make:notification Batch\\SendAutoMailFinishedNotification
php artisan make:notification Batch\\StepDirectMailFinishedNotification
```

## マイグレーションの作成とモデル
```
php artisan make:migration create_system_logs_table
php artisan make:model Models\\SystemLogs
php artisan ide-helper:models -n
```

## キューワーカーの起動
試行回数3回の例
```
php artisan queue:work --tries=3
```