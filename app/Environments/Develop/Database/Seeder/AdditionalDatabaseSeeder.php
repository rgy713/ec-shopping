<?php


namespace App\Environments\Develop\Database\Seeder;

use Illuminate\Database\Seeder;

class AdditionalDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //開発環境用のデータベースシーディング
        $this->call(TestAdminSeeder::class);

        //サンプル広告媒体データ
        $this->call(SampleAdvertisingMediaSeeder::class);
        //サンプル顧客データ
        $this->call(SampleCustomerSeeder::class);
        //サンプル受注データ
        $this->call(SampleOrderSeeder::class);
        //サンプル定期データ
        $this->call(SamplePeriodicOrderSeeder::class);

        //2018年の休日設定
        $this->call(Init2018HolidaySettingsSeeder::class);

        //システム設定
        $this->call(InitSystemSettingSeeder::class);

        //メールテンプレート関連
        $this->call(MailTemplatesSeeder::class);

        //タグ関連
        $this->call(InitUserPagesSeeder::class);

        //ステップDM History
        $this->call(SampleStepdmHistoriesSeeder::class);

        //ステップDM History Detail
        $this->call(SampleStepdmHistoryDetailsSeeder::class);
    }

}