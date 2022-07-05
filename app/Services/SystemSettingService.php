<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 4/8/2019
 * Time: 4:44 PM
 */

namespace App\Services;

use App\Models\SystemSetting;

class SystemSettingService
{
    /**
     * @return mixed
     * @throws \App\Exceptions\InvalidDataStateException
     */
    public function getSystemSetting()
    {
        return SystemSetting::get();
    }

    /**
     * @param $params
     */
    public function update($params)
    {
        $system_setting = SystemSetting::find($params['id']);
        $system_setting->system_sender_mail_address = $params['system_sender_mail_address'];
        $system_setting->system_sender_signature = $params['system_sender_signature'];
        $system_setting->system_admin_mail_address = $params['system_admin_mail_address'];
        $system_setting->operation_admin_mail_address = $params['operation_admin_mail_address'];
        $system_setting->save();
    }
}