<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\PeriodicOrderPairRelationshipType;

class InitPeriodicOrderPairRelationshipTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new PeriodicOrderPairRelationshipType();
        $model->id=1;
        $model->name="重複疑い";
        $model->rank=1;
        $model->description="同一の顧客ID、定期詳細に含まれる商品のラインナップが一致。";
        $model->save();

        $model = new PeriodicOrderPairRelationshipType();
        $model->id=101;
        $model->name="統合済み";
        $model->rank=101;
        $model->description="管理者の操作、または自動統合バッチ処理により、統合処理が行われたことを示す。";
        $model->save();

        $model = new PeriodicOrderPairRelationshipType();
        $model->id=102;
        $model->name="統合しない処理済み";
        $model->rank=102;
        $model->description="管理者の操作により「統合しない」処理が行われたことを示す。";
        $model->save();

    }
}
