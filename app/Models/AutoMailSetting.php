<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoMailSetting extends Model
{
    public function mailTemplates()
    {
        return $this->belongsTo(MailTemplate::class, "mail_template_id", "id");
    }

    public function autoMailItemLineup()
    {
        return $this->hasMany(AutoMailItemLineup::class, "auto_mail_setting_id", "id");
    }
}
