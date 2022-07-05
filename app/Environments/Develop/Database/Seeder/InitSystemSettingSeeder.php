<?php

namespace App\Environments\Develop\Database\Seeder;

use App\Models\Order;
use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class InitSystemSettingSeeder extends Seeder
{
    public function run()
    {
        $model=new SystemSetting();
        $model->system_admin_mail_address="dev_system_admin@balocco.info";
        $model->operation_admin_mail_address="dev_operation_admin@balocco.info";
        $model->system_sender_mail_address="dev_mail_sender@balocco.info";
        $model->system_sender_signature="開発テストメール";
        $model->save();

    }

}