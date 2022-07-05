<?php


namespace Tests\Unit\Events;

use App\Common\OperationAdministrator;
use App\Events\AdditionalShippingAddressModifiedByCustomer;
use App\Events\AdditionalShippingAddressRegisteredByCustomer;
use App\Events\CustomerAddressModifiedByCustomer;
use App\Mail\TemplateMail;
use App\Models\AdditionalShippingAddress;
use App\Models\Customer;
use App\Notifications\CustomerAddressModifiedNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CustomerAddressModifiedByCustomerTest extends TestCase
{
    use DatabaseTransactions;

    CONST FACTORY_KEY = 'for_dev';

    /**
     * CustomerAddressModifiedByCustomerを発火
     * @param $model
     * @param $expected
     * @author k.yamamoto@balocco.info
     * @dataProvider customerAddressModifiedDataProvider
     */
    public function testFireEvent($model, $expected)
    {
        Notification::fake();
        //運営管理者オブジェクト
        $operationAdministrator = new OperationAdministrator();

        //顧客情報を一切変更せずにイベント発火
        event(new CustomerAddressModifiedByCustomer($model->getOriginal(), $model->getAttributes()));


        //通知の送信を確認
        if ($expected) {
            //通知される
            Notification::assertSentTo([$operationAdministrator], CustomerAddressModifiedNotification::class);
        } else {
            //通知されない
            Notification::assertNotSentTo([$operationAdministrator], CustomerAddressModifiedNotification::class);
        }
    }

    /**
     * 通知がシステムログに保存されることを検証
     * @covers \App\Notifications\CustomerAddressModifiedNotification::getSystemMessage
     * @author k.yamamoto@balocco.info
     */
    public function testNotificationToSystemLog(){
        $models = factory(Customer::class, self::FACTORY_KEY, 1)->create();
        /** @var Customer $model */
        $model=$models->get(0);
        $model->address01 = '住所を変更して通知実行';
        event(new CustomerAddressModifiedByCustomer($model->getOriginal(), $model->getAttributes()));

        //通知内容が保存されていることを検証
        $this->assertDatabaseHas('system_logs', [
            'type' => 'App\Notifications\CustomerAddressModifiedNotification',
            'data' => '{"message":"\u9867\u5ba2\u60c5\u5831(ID:'.$model->id.') \u304c\u30e6\u30fc\u30b6\u30fc\u306b\u3088\u308a\u66f4\u65b0\u3055\u308c\u307e\u3057\u305f","level":"info"}',
        ]);
    }

    /**
     * @return array
     * @covers \App\Events\CustomerAddressModifiedByCustomer
     * @covers \App\Listeners\CustomerAddressModifiedNotifier
     * @author k.yamamoto@balocco.info
     */
    public function customerAddressModifiedDataProvider()
    {
        //dataProvider内の処理用にcreateApplication()
        $this->createApplication();

        //テストケースとして使用する顧客データを生成
        $models = factory(Customer::class, self::FACTORY_KEY, 7)->create();
        //変数初期化
        $testCases = [];

        /**
         * テストケース0：変更なし
         * 通知が行われないことを期待
         */
        $testCases[0] = [$models->get(0), false];

        /**
         * テストケース1：通知対象かどうかの判定に利用される住所関連列4点すべてを変更したモデル
         * 通知が行われることを期待
         */
        $tempTestCase = $models->get(1);
        /** @var Customer $tempTestCase */
        $tempTestCase->address01 = '住所1を変更すると通知が行われる' . uniqid();
        $tempTestCase->address02 = '住所2を変更すると通知が行われる' . uniqid();
        if ($tempTestCase->prefecture_id === 1) {
            $tempTestCase->prefecture_id = 2;
        } else {
            $tempTestCase->prefecture_id = 1;
        }
        if ($tempTestCase->zipcode === '9999999') {
            $tempTestCase->zipcode = '1111111';
        } else {
            $tempTestCase->zipcode = '9999999';
        }
        //テストケース2を入れる（通知が行われることを期待）
        $testCases[1] = [$tempTestCase, true];

        /**
         * テストケース2：通知対象以外の列を変更したモデル
         * 通知が行われないことを期待
         */
        $tempTestCase = $models->get(2);
        $tempTestCase->name01 = '変更された苗字';
        $tempTestCase->name02 = '変更された名前';
        $tempTestCase->kana01 = 'ヘンコウサレタミョウジ';
        $tempTestCase->kana01 = 'ヘンコウサレタナマエ';
        $tempTestCase->phone_number01 = '111111111111';
        $tempTestCase->phone_number02 = '222222222222';
        $tempTestCase->requests_for_delivery = '変更';
        $tempTestCase->email = 'modified_email';
        $tempTestCase->birthday = Carbon::now();
        $tempTestCase->first_buy_date = Carbon::now();
        $tempTestCase->last_buy_date = Carbon::now();
        $tempTestCase->buy_times = $tempTestCase->buy_times + 1;
        $tempTestCase->buy_total = $tempTestCase->buy_total + 1;
        $tempTestCase->buy_volume = $tempTestCase->buy_volume + 1;
        $tempTestCase->no_phone_call_flag = !$tempTestCase->no_phone_call_flag;
        $tempTestCase->mail_magazine_flag = !$tempTestCase->mail_magazine_flag;
        $tempTestCase->dm_flag = !$tempTestCase->dm_flag;
        $tempTestCase->wholesale_flag = !$tempTestCase->wholesale_flag;
        if ($tempTestCase->pfm_status_id === 3) {
            $tempTestCase->pfm_status_id = 2;
        } else {
            $tempTestCase->pfm_status_id = 3;
        }
        if ($tempTestCase->advertising_media_code = 9999) {
            $tempTestCase->advertising_media_code = 1111;
        } else {
            $tempTestCase->advertising_media_code = 9999;
        }
        if ($tempTestCase->customer_status_id === 3) {
            $tempTestCase->customer_status_id = 2;
        } else {
            $tempTestCase->customer_status_id = 3;
        }
        $tempTestCase->created_at = Carbon::now();
        $tempTestCase->updated_at = Carbon::now();
        //
        $testCases[2] = [$tempTestCase, false];

        /**
         * テストケース3：住所関連列を1点のみ変更したモデル（住所1）
         */
        $tempTestCase = $models->get(3);
        $tempTestCase->address01='住所1だけ変更、通知されるはず';
        $testCases[3] = [$tempTestCase, true];

        /**
         * テストケース4：住所関連列を1点のみ変更したモデル（住所2）
         */
        $tempTestCase = $models->get(4);
        $tempTestCase->address02='住所2だけ変更、通知されるはず';
        $testCases[4] = [$tempTestCase, true];

        /**
         * テストケース5：住所関連列を1点のみ変更したモデル（都道府県ID）
         */
        $tempTestCase = $models->get(5);
        if ($tempTestCase->prefecture_id === 1) {
            $tempTestCase->prefecture_id = 2;
        } else {
            $tempTestCase->prefecture_id = 1;
        }
        $testCases[5] = [$tempTestCase, true];

        /**
         * テストケース6：住所関連列を1点のみ変更したモデル（郵便番号）
         */
        $tempTestCase = $models->get(6);
        if ($tempTestCase->zipcode === '9999999') {
            $tempTestCase->zipcode = '1111111';
        } else {
            $tempTestCase->zipcode = '9999999';
        }
        $testCases[6] = [$tempTestCase, true];


        return $testCases;
    }

}