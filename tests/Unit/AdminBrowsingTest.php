<?php


namespace Tests\Unit;

use App\Models\Admin;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminBrowsingTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 管理画面内の全てのルートで画面が表示されることを検査。
     * 管理画面にルーティングを追加した場合、テストケースを追加する。
     * （ログイン、ログアウトは除く）
     * TODO:各画面の実装が進んだら、パラメータ付きのURLに対する検査結果がDBのレコード状態に依存する（存在しないIDを指定するとエラーになるなど）ようになるので、テスト用データ投入処理等も含むテストに書き換え
     * @dataProvider adminLoginPages
     */
    public function testAdminLoginPages($route, $parameters)
    {
        //ログインユーザ
        $admin = factory(Admin::class)->create();


        //ルート名からパスを作成
        $path = route($route, $parameters, false);
        $responseWithLogin = $this->actingAs($admin, 'admin')->get($path);

        $responseWithLogin->assertStatus(200);
    }

    /**
     * testSimpleAdminLoginPagesのデータプロバイダ。
     * GETメソッドでアクセス可能
     * ルート名だけ指定すれば表示できる
     * パラメータの必要ない単純な画面
     * ログイン済の状態で表示できる画面
     * のみテスト対象。
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function adminLoginPages()
    {
        return [
            ['admin.order.delivery_slip', []],
            ['admin.media.import', []],
            ['admin.import.call_center', []],
            ['admin.import.unreachable_email', []],
            ['admin.home', []],
            ['admin.mail.system', []],
            ['admin.account.list', []],
            ['admin.account.create', []],
            ['admin.account.edit', ['id' => 1]],
            ['admin.system.holiday', []],
            ['admin.system.tax', []],
            ['admin.stepdm.download', []],
            ['admin.stepdm.setting', []],
            ['admin.mail.template', []],
            ['admin.mail.template.edit', ['id' => 1]],
            ['admin.mail.template.create', ['type' => 1]],
            ['admin.mail.layout', []],
            ['admin.mail.layout.edit', ['id' => 1]],
            ['admin.mail.layout.create', []],
            ['admin.mail.send.order', ['id' => 1]],
            ['admin.mail.trigger', ['id' => 1]],
            ['admin.customer.basic_search', []],
            ['admin.customer.detailed_search', []],
            ['admin.customer.info', ['id' => 1]],
            ['admin.customer.create', []],
            ['admin.customer.marketing_search', []],
            ['admin.customer.popup.create', []],
            ['admin.media.search', []],
            ['admin.media.create', []],
            ['admin.media.edit', ['id' => 1]],
            ['admin.media.asp', []],
            ['admin.media.summary', []],
            ['admin.media.tag.list', []],
            ['admin.media.tag.create', []],
            ['admin.media.tag.edit', ['id' => 1]],
            ['admin.media.tag.page.info', ['id' => 1]],
            ['admin.order.search', []],
            ['admin.order.create', []],
            ['admin.order.edit', ['id' => 1]],
            ['admin.order.popup.edit', ['id' => 1]],
            ['admin.periodic.create', []],
            ['admin.periodic.edit', ['id' => 1]],
            ['admin.order.shipping', []],
            ['admin.order.utility', []],
            ['admin.periodic.search', []],
            ['admin.product.search', []],
            ['admin.product.create', []],
            ['admin.product.edit', ['id' => 1]],
            ['admin.product.download', []],
            ['admin.sales.summary.wholesale', []],
            ['admin.sales.summary.accounting', []],
            ['admin.sales.summary.marketing', []],
            ['admin.sales.summary.periodic_count', []],
            ['admin.sales.summary.age', []],
            ['admin.sales.summary.payment', []],
            ['admin.product.download', []],
            ['admin.media.import.confirm', []],
            ['admin.account.disable', []],
            ['admin.account.enable', []],
            ['admin.system.csv_setting', [1]],

        ];
    }


//['admin.login'],
//['admin.logout'],

}