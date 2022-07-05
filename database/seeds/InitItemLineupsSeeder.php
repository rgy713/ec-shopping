<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\ItemLineup;

class InitItemLineupsSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $model = new ItemLineup();
        $model->id = 1;
        $model->name = 'クリアゲルクレンズ';
        $model->rank = 1;
        $model->abbreviation = "CGC";
        $model->save();

        $model = new ItemLineup();
        $model->id = 2;
        $model->name = 'クリアクレンジング';
        $model->rank = 2;
        $model->abbreviation = "CC";
        $model->save();

        $model = new ItemLineup();
        $model->id = 3;
        $model->name = 'ミネラルモイストソープ';
        $model->rank = 3;
        $model->abbreviation = "MMS";
        $model->save();

        $model = new ItemLineup();
        $model->id = 4;
        $model->name = 'リファイニングミスト';
        $model->rank = 4;
        $model->abbreviation = "RM";
        $model->save();

        $model = new ItemLineup();
        $model->id = 5;
        $model->name = 'トリプルリペア';
        $model->rank = 5;
        $model->abbreviation = "TR";
        $model->save();

        $model = new ItemLineup();
        $model->id = 6;
        $model->name = '同梱物（削除予定）';
        $model->rank = 5;
        $model->abbreviation = "***";
        $model->save();

    }
}
