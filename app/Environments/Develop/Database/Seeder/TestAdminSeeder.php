<?php

namespace App\Environments\Develop\Database\Seeder;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class TestAdminSeeder extends Seeder
{

    public function run()
    {
        $user = new Admin();
        $user->account = "admin@balocco.info";
        $user->password = bcrypt("123456");
        $user->name = 'テストアカウント1';
        $user->department = '所属';
        $user->admin_role_id = 1;
        $user->save();

        $user = new Admin();
        $user->account = "role2@balocco.info";
        $user->password = bcrypt("123456");
        $user->name = 'テストアカウント2';
        $user->department = '運営管理者';
        $user->admin_role_id = 2;
        $user->save();

        $user = new Admin();
        $user->account = "role3@balocco.info";
        $user->password = bcrypt("123456");
        $user->name = 'テストアカウント3';
        $user->department = 'サポートデスク';
        $user->admin_role_id = 3;
        $user->save();

        $user = new Admin();
        $user->account = "role4@balocco.info";
        $user->password = bcrypt("123456");
        $user->name = 'テストアカウント4';
        $user->department = 'オペレーター';
        $user->admin_role_id = 4;
        $user->save();

        $user = new Admin();
        $user->account = "role5@balocco.info";
        $user->password = bcrypt("123456");
        $user->name = 'テストアカウント5';
        $user->department = '登録・発送';
        $user->admin_role_id = 5;
        $user->save();

        $user = new Admin();
        $user->account = "role6@balocco.info";
        $user->password = bcrypt("123456");
        $user->name = 'テストアカウント6';
        $user->department = 'コールセンター';
        $user->admin_role_id = 6;
        $user->save();

        $user = new Admin();
        $user->account = "role7@balocco.info";
        $user->password = bcrypt("123456");
        $user->name = 'テストアカウント7';
        $user->department = '出荷業者';
        $user->admin_role_id = 7;
        $user->enabled = false;
        $user->save();

        $user = new Admin();
        $user->account = "role8@balocco.info";
        $user->password = bcrypt("123456");
        $user->name = 'テストアカウント8';
        $user->department = 'ステップDM閲覧';
        $user->admin_role_id = 8;
        $user->save();
    }

}