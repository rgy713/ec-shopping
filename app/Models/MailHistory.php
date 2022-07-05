<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MailTemplate;

class MailHistory extends Model
{
    public function mailTemplate()
    {
        return $this->hasOne(MailTemplate::class,'id','mail_template_id');
    }
}
