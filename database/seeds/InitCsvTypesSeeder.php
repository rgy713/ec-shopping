<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\CsvType;

class InitCsvTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [1, '顧客管理CSV', true],
            [2, '受注管理CSV', true],
            [3, '定期管理CSV', true],
            [4, '配送管理CSV', true],
            [5, '会計部門集計_計上売上CSV', false],
            [6, '会計部門集計_キャンセルCSV', false],
            [7, '卸集計CSV', false],
            [8, '年代別集計CSV', false],
            [9, '支払い方法別集計CSV', false],
            [10, '購入不可設定CSV', false],
            [11, '購入時警告対象設定CSV', false],
            [12, '定期同梱物設定', false],
            [13, '広告媒体CSV', false],
            [14, 'ステップDMCSV', false],
            [15, 'マーケ部門サマリー', false],
        ];

        foreach ($data as $record) {
            $model = new CsvType();
            $model->id = $record[0];
            $model->rank = $record[0];
            $model->name = $record[1];
            $model->configurable = $record[2];
            $model->save();
        }

    }
}
