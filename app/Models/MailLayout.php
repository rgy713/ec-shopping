<?php

namespace App\Models;

use App\Models\Interfaces\KeyValueListModelInterface;
use App\Models\Traits\DefaultKeyValueListTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\MailTemplate;

class MailLayout extends Model implements KeyValueListModelInterface
{
    use DefaultKeyValueListTrait;

    /**
     * rank列が無いため、id列でソートする指定。
     * @return string
     * @see DefaultKeyValueListTrait
     * @author k.yamamoto@balocco.info
     */
    static public function getColumnNameOfListOrderBy()
    {
        return 'id';
    }

    public function mailTemplates()
    {
        return $this->hasMany(MailTemplate::class,'mail_layout_id','id');
    }
}
