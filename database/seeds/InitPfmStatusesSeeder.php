<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\PfmStatus;

class InitPfmStatusesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [
            ["1", "プラチナ", "0"],
            ["2", "ゴールド", "0"],
            ["3", "優良", "0"],
            ["4", "安定", "0"],
            ["5", "成長", "0"],
            ["6", "未開発", "0"],
            ["7", "新規", "0"],
            ["8", "プラチナ離脱", "0"],
            ["9", "ゴールド離脱", "0"],
            ["10", "優良離脱", "0"],
            ["11", "安定離脱", "0"],
            ["12", "成長離脱", "0"],
            ["13", "未開発離脱", "0"],
            ["14", "新規離脱", "0"],
            ["0", "登録のみ", "0"],
        ];

        foreach ($data as $item){
            $model = new PfmStatus();
            $model->id=$item[0];
            $model->name=$item[1];
            $model->rank=$item[2];
            $model->save();
        }


    }
}
