<?php

use Illuminate\Database\Seeder;
use App\Models\Masters\AdminRole;

class InitAdminRolesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data=[
            ["1","システム管理者","バロッコのみ利用する","0"],
            ["2","運営管理者","旧システムの「店舗オーナー」と同等","10"],
            ["3","サポートデスク","正社員の新入社員に付与する想定の権限。運営管理者と同等の画面閲覧が可能だが、編集、削除等の権限が一部制限される。","10"],
            ["4","オペレーター","派遣社員、電話対応業務を行う担当者に付与する想定の権限。","10"],
            ["5","登録・発送","派遣社員、登録発送業務を行う担当者に付与する想定の権限。","10"],
            ["6","コールセンター","コールセンター業務の業務委託先に付与する想定の権限。","10"],
            ["7","経理補佐","経理業務を補佐する派遣社員担当者に付与する想定の権限。","10"],
            ["8","出荷業者","出荷用のCSVデータをダウンロードすることのみできる権限。当面、利用はしないが、配送業者変更に備えて作成する。","10"],
            ["9","ステップDM閲覧","DMの発送業務を行う業務委託先に付与する想定の権限。DM関連情報のダウンロードのみ可能","10"],
        ];

        foreach ($data as $item){
            $model = new AdminRole();
            $model->id=$item[0];
            $model->name=$item[1];
            $model->remark=$item[2];
            $model->expiration_date=$item[3];
            $model->save();
        }

    }
}
