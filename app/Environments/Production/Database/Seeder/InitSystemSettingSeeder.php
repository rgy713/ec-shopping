<?php

namespace App\Environments\Producttion\Database\Seeder;

use App\Models\Order;
use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class InitSystemSettingSeeder extends Seeder
{
    public function run()
    {
        $model=new SystemSetting();
        $model->system_admin_mail_address="medicalcourt@balocco.info";
        $model->operation_admin_mail_address="operation@fleuri.cc";
        $model->system_sender_mail_address="customer@fleuri.cc";
        $model->system_sender_signature="フルリサポートデスク";
        $model->save();

    }

}