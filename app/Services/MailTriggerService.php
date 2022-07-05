<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 4/19/2019
 * Time: 6:18 PM
 */

namespace App\Services;

use App\Models\AutoMailSetting;
use App\Models\AutoMailItemLineup;

class MailTriggerService
{
    public function getTrigger($mail_template_id)
    {
        $info = AutoMailSetting::where('mail_template_id', $mail_template_id)->first();
        return $info;
    }

    public function getItemLineups($id)
    {
        $lineups = AutoMailItemLineup::where("auto_mail_setting_id", $id)->get();
        return $lineups;
    }

    public function create($params)
    {
        $trigger = AutoMailSetting::where("mail_template_id", $params["id"])->first();
        if(!isset($trigger))
            $trigger = new AutoMailSetting();
        $trigger->mail_template_id = $params["id"];
        $trigger->enabled = $params["mail_setting_enabled"];
        $trigger->order_method = $params["mail_setting_order_method"];
        $trigger->elapsed_days = $params["elapsed_days"];
        $trigger->first_purchase_only_flag = $params["first_purchase_only_flag"];
        $trigger->regular_member_only_flag = $params["regular_member_only_flag"];
        $trigger->customer_mail_magazine_flag = $params["customer_mail_magazine_flag"];
        $trigger->save();
        AutoMailItemLineup::where("auto_mail_setting_id", $trigger->id)->delete();
        if(isset($params["item_linup_id"]))
            foreach($params["item_linup_id"] as $lineup_id)
            {
                $lineup = new AutoMailItemLineup();
                $lineup->auto_mail_setting_id = $trigger->id;
                $lineup->item_lineup_id = $lineup_id;
                $lineup->save();
            }
    }
}