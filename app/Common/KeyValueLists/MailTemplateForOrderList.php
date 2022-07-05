<?php

namespace App\Common\KeyValueLists;

use App\Models\MailTemplate;

class MailTemplateForOrderList extends KeyValueList
{
    /**
     * @var MailTemplate
     */
    protected $model;

    /**
     * MailTemplateForOrderList constructor.
     */
    public function __construct($items = [])
    {
        $this->model = app(MailTemplate::class);;
        parent::__construct($items);
    }

    public function definition(): array
    {
        //TODO:修正 メール履歴画面で選択可能であることを示すフラグ列をテーブルに追加し、IDハードコーディングをフラグ条件に書き換え
        return $this->model->where("mail_template_type_id", "=", 1)->whereIn('id',[1,4,6,12])->get()->pluck("name", "id")->toArray();
    }

}