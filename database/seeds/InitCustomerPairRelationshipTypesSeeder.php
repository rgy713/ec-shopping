<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\CustomerPairRelationshipType;

class InitCustomerPairRelationshipTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new CustomerPairRelationshipType();
        $model->id=1;
        $model->name="重複疑い（2項目）";
        $model->rank=1;
        $model->description="重複疑いチェック項目中、2項目以上が該当している顧客の組である。";
        $model->save();

        $model = new CustomerPairRelationshipType();
        $model->id=2;
        $model->name="重複疑い（3項目）";
        $model->rank=2;
        $model->description="重複疑いチェック項目中、3項目以上が該当している顧客の組である。";
        $model->save();

        $model = new CustomerPairRelationshipType();
        $model->id=3;
        $model->name="重複疑い（4項目）";
        $model->rank=3;
        $model->description="重複疑いチェック項目中、4項目全てが該当している顧客の組である。このIDが割り当てられている組は、自動統合バッチ処理の対象となる。";
        $model->save();

        $model = new CustomerPairRelationshipType();
        $model->id=101;
        $model->name="統合済み";
        $model->rank=101;
        $model->description="管理者の操作、または自動統合バッチ処理により、統合処理が行われたことを示す。";
        $model->save();

        $model = new CustomerPairRelationshipType();
        $model->id=102;
        $model->name="統合しない処理済み";
        $model->rank=102;
        $model->description="管理者の操作により「統合しない」処理が行われたことを示す。";
        $model->save();

    }
}
