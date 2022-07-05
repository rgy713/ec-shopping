<?php


namespace App\Services;

use App\Events\PairOfCustomersMerged;
use App\Models\Customer;
use App\Models\CustomerPairRelationship;
use App\Models\ShopMemo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * 複アカウントチェック、顧客統合等の処理を実装するクラス。
 * Class MultipleAccountsService
 * @package App\Services
 * @author k.yamamoto@balocco.info
 */
class MultipleAccountsService
{
    /**
     * 引数の$customerIdと重複した人物であることが疑われるデータを顧客テーブルから検索する。
     * 条件は、1.名前が一致 2.住所が一致 3.電話番号が一致 4.誕生日が一致 の4点。
     * 住所については、1.スペースを取り除き 2.小文字→大文字変換した上で、3.全角英数と全角ハイフン8種を半角英数、半角「-」に置換して比較。
     * @param $customerId
     * @return array
     * @author k.yamamoto@balocco.info
     */
    public function getSuspiciousCustomersWith($customerId)
    {
        $sql = "
            -- 共通テーブル式 で先にサブクエリを定義
            WITH CTE AS (
                SELECT 
                    c2.* ,
                    -- 以下4つのcase文の結果の和としてcount_of_condition_satisfiedを定義。
                    -- 1.名前が一致している場合に1、一致していない場合に0を返すcase文 
                    case when c1.name01 || c1.name02 =c2.name01||c2.name02 then 1 else 0 end +
                    -- 2.住所情報が一致している場合に1、一致していない場合に0を返すcase文
                    case when translate(upper(c1.prefecture_id||c1.address01||c1.address02),'０１２３４５６７８９ＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺー‐‑–—―−ｰ 　','0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ--------') ILIKE translate(upper(c2.prefecture_id||c2.address01||c2.address02),'０１２３４５６７８９ＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺー‐‑–—―−ｰ 　','0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ--------') then 1 else 0 end +
                    -- 3.電話番号が一致している場合に1、一致していない場合に0を返すcase文
                    case when c1.phone_number01 = c2.phone_number01 then 1 else 0 end +
                    -- 4.誕生日が一致している場合に1、一致していない場合に0を返すcase文
                    case when c1.birthday = c2.birthday then 1 else 0 end as count_of_condition_satisfied
                FROM
                    customers c1 JOIN 
                    -- 結合条件で、論削されているレコード、有効化されていないレコードを除外
                    customers c2 ON 
                    (
                        c1.deleted_at IS NULL AND 
                        c1.confirmed_timestamp IS NOT NULL AND 
                        c2.deleted_at IS NULL AND 
                        c2.confirmed_timestamp IS NOT NULL AND 
                        -- 検査対象の顧客IDそのものは比較対象から除外
                        c1.id <> c2.id
                    )
                WHERE 
                    -- 検査対象の顧客IDを指定（顧客IDを指定しないと、顧客テーブル✕顧客テーブルの全件が対象となり時間がかかる）
                    c1.id = ? AND
                    -- 検索条件のいずれか1つは満たしていることを調べて絞り込み（レスポンスタイム短縮のため）
                    (
                        c1.name01 || c1.name02 =c2.name01||c2.name02 OR
                        translate(upper(c1.prefecture_id||c1.address01||c1.address02),'０１２３４５６７８９ＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺー‐‑–—―−ｰ 　','0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ--------') ILIKE translate(upper(c2.prefecture_id||c2.address01||c2.address02),'０１２３４５６７８９ＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺー‐‑–—―−ｰ 　','0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ--------') OR
                        c1.phone_number01 = c2.phone_number01 OR 
                        c1.birthday = c2.birthday 
                    ) 
            )
            SELECT 
                CTE.*
            FROM 
                CTE
            WHERE 
                count_of_condition_satisfied > 1
        ";

        return DB::select($sql, [$customerId]);
    }

    /**
     * 複アカウントチェック判定により、複アカウントである可能性が高いと判定された顧客データの組を抽出する。
     * //TODO:実装 customer_pair_relationshipsテーブルを参照し、customer_pair_relationship_type_id = 3 であるレコードを1件抽出
     */
    public function getStronglySuspectedCustomer(){

    }


    /**
     * //TODO:実装
     * 第一引数、第二引数で指定された2つの顧客情報を統合する。
     * @param integer $customerIdToDelete 統合により削除する顧客ID
     * @param integer $customerIdToLive 統合後の顧客ID
     * @param array $parameters 第二引数の顧客IDを更新する内容を持つ一次元配列。更新対象の列名をキー、更新する値を値として持つ配列を入力する。
     * @return Customer 統合後の顧客情報を返す。
     */
    public function mergePairOfCustomers(int $customerIdToDelete,int $customerIdToLive, array $customer_pair_relationship){

        $customerA = app(Customer::class)->find($customerIdToDelete);
        $customerB = app(Customer::class)->find($customerIdToLive);

        if(isset($customerA->advertising_media_code)){
            $customerB->advertising_media_code = $customerA->advertising_media_code;
            $customerB->save();
        }

        foreach($customerA->orders as $order)
        {
            $order->customer_id = $customerB->id;
            $order->save();
        }
        foreach ($customerA->periodicOrders as $periodicOrder)
        {
            $periodicOrder->customer_id= $customerB->id;
            $periodicOrder->save();
        }
        foreach ($customerA->shopMemos as $shopMemo)
        {
            $shopMemo->customer_id = $customerB->id;
            $shopMemo->save();
        }
        $customerA->delete();
        $customer_pair_relationship->customer_pair_relationship_type_id = 101;
        $customer_pair_relationship->save();

        $count = app(CustomerPairRelationship::class)
            ->where(function ($q) use ($customerB) {
                $q->orWhere("customer_id_a", $customerB->id);
                $q->orWhere("customer_id_b", $customerB->id);
            })
            ->whereIn("customer_pair_relationship_type_id",[1,2,3])
            ->count();
        if($count==0){
            $customerB->sub_account_warning_flag = false;
            $customerB->save();
        }

        event(new PairOfCustomersMerged($customerA, $customerB));

        app(OrderService::class)->updatePurchaseInfoOfCustomer($customerIdToLive);
        app(OrderService::class)->updateOrderCountOfCustomerWithoutCancel($customerIdToLive);
        $this->createMergedShopMemo($customerIdToDelete, $customerIdToLive, $customerB->advertising_media_code);
    }

    /**
     * TODO:実装
     * 共通処理 #230
     */
    public function createMergedShopMemo(int $customerIdToDelete, int $customerIdToLive, int $advertising_media_code)
    {
        $shop_memo = new ShopMemo();
        $now = Carbon::now()->toDateTimeString();
        $shop_memo->note = "{$now} 顧客統合 \n 
                            顧客:{$customerIdToDelete} を削除。\n
                            顧客:{$customerIdToLive} に統合。\n
                            統合時、広告番号を {$advertising_media_code} に設定。";
        $shop_memo->customer_id = $customerIdToLive;
        $shop_memo->created_by = 1;
        $shop_memo->save();
    }

}