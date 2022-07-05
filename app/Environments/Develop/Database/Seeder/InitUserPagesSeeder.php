<?php

namespace App\Environments\Develop\Database\Seeder;

use App\Models\Masters\UserPage;
use Illuminate\Database\Seeder;

class InitUserPagesSeeder extends Seeder
{
    public function run()
    {
        $model=new UserPage();
        $model->id=1;
        $model->name='購入完了画面';
        $model->group='購入フロー';
        $model->rank=1;
        $model->pc=true;
        $model->sp=true;
        $model->route_name='user.shopping.complete';
        $model->path='/shopping/complete';
        $model->save();

        $model=new UserPage();
        $model->id=2;
        $model->name='支払い方法選択画面';
        $model->group='購入フロー';
        $model->rank=2;
        $model->pc=true;
        $model->sp=true;
        $model->route_name='user.shopping.payment';
        $model->path='/shopping/payment';
        $model->save();

        $model=new UserPage();
        $model->id=3;
        $model->name='トップページ';
        $model->group='その他';
        $model->rank=3;
        $model->pc=true;
        $model->sp=true;
        $model->route_name='user.top';
        $model->path='/';
        $model->save();

        $model=new UserPage();
        $model->id=4;
        $model->name='マイページログイン画面';
        $model->group='マイページ';
        $model->rank=4;
        $model->pc=true;
        $model->sp=true;
        $model->route_name='user.mypage.login';
        $model->path='/mypage/login';
        $model->save();
    }

}