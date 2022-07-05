<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 4/22/2019
 * Time: 11:24 AM
 */

namespace App\Common\KeyValueLists;

use App\Models\MailTemplate;

class MailLayoutIdListForMailTemplate extends KeyValueList
{
    /**
     * MailLayoutIdListForMailTemplate constructor.
     * @param MailTemplate $model
     */
    public function __construct(MailTemplate $mailTemplate)
    {
        $this->model = $mailTemplate;
        parent::__construct($this->definition());

    }

    public function definition(): array
    {
        return $this->model->get()->pluck('mail_layout_id','id')->toArray();
    }
}