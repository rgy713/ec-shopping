<?php

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 * @author k.yamamoto@balocco.info
 */
class DatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        //全環境共通で実行されるSeeder、マスタ系データの投入等を行う
//        $this->call(InitSystemEventsSeeder::class);
        //管理画面権限
        $this->call(InitAdminRolesSeeder::class);
        $this->call(InitAdminRoleRouteSettingsSeeder::class);

        //2019年休業日
        $this->call(Init2019HolidaySettingsSeeder::class);

        //商品系
        $this->call(InitItemLineupsSeeder::class);
        $this->call(InitProductDeliveryTypesSeeder::class);
        $this->call(InitUndeliveredSummaryClassificationsSeeder::class);
        $this->call(InitSalesRoutesSeeder::class);
        $this->call(InitSalesTargetsSeeder::class);
        $this->call(InitStockKeepingUnitsSeeder::class);

        //購入時警告マスタ
        $this->call(InitPurchaseWarningTypesSeeder::class);

        //商品データ移行
        $this->call(InitProductsSeeder::class);

        //固定定期間隔情報
        $this->call(InitFixedPeriodicIntervalSeeder::class);
        //固定支払い方法情報
        $this->call(InitFixedPaymentMethodProductSeeder::class);


        //同梱物設定
        $this->call(InitItemBundleSettingsSeeder::class);


        //顧客・受注系
        $this->call(InitPrefecturesSeeder::class);
        $this->call(InitCustomerStatusesSeeder::class);
        $this->call(InitOrderStatusesSeeder::class);
        $this->call(InitTaxSettingsSeeder::class);
        $this->call(InitPfmStatusesSeeder::class);
        $this->call(InitRegistrationRoutesSeeder::class);
        $this->call(InitDeliveriesSeeder::class);
        $this->call(InitDeliveryFeesSeeder::class);
        $this->call(InitDeliveryTimesSeeder::class);
        $this->call(InitLeadtimesSeeder::class);
        $this->call(InitPaymentsSeeder::class);
        $this->call(InitPeriodicIntervalTypesSeeder::class);

        //受注ステータスの状態遷移表
        $this->call(InitOrderStateTransitionSeeder::class);

        //広告媒体系
        $this->call(InitMediumTypesSeeder::class);
        $this->call(InitAspMediaSeeder::class);
        $this->call(InitAdvertisingMediaSummaryGroupsSeeder::class);

        //タグ
        $this->call(InitTagDevicesSeeder::class);
        $this->call(InitTagPositionsSeeder::class);


        //ステップDM系
        $this->call(InitStepdmTypesSeeder::class);
        $this->call(InitStepdmSettingsSeeder::class);

        //メール系

        $this->call(InitMailLayoutsSeeder::class);//レイアウト
        $this->call(InitMailTemplateTypesSeeder::class);
        $this->call(InitMailTemplatesSeeder::class);//テンプレート

        //定期状況マスタ
//        $this->call(InitPeriodicOrderStatusesSeeder::class);

        //購入経路マスタ
        $this->call(InitPurchaseRoutesSeeder::class);

        //CSVファイル設定
        $this->call(InitCsvTypesSeeder::class);
        $this->call(InitCsvOutputSettingsSeeder::class);

        //顧客どうしの関係の種類マスタ（重複/統合しない、など）
        $this->call(InitCustomerPairRelationshipTypesSeeder::class);

        //定期の組み合わせ関係マスタ（重複/統合しない、など）
        $this->call(InitPeriodicOrderPairRelationshipTypesSeeder::class);

        //各環境毎に実行されるSeeder
        $additionalSeeder = resolve(AdditionalDatabaseSeeder::class);
        $this->call(get_class($additionalSeeder));

    }
}
