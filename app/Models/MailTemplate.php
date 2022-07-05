<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
    public function layout()
    {
        return $this->hasOne(MailLayout::class,'id','mail_layout_id');
    }
}
