<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 4/19/2019
 * Time: 12:04 PM
 */

namespace App\Common\KeyValueLists;

use App\Models\MailLayout;

class MailLayoutFooterFilePathList extends KeyValueList
{
    /** @var MailLayout $model */
    protected $model;

    /**
     * ItemLineupList constructor.
     * @param MailLayout $model
     */
    public function __construct(MailLayout $model)
    {
        $this->model = $model;
        parent::__construct($this->definition());

    }

    public function definition(): array
    {
        return $this->model->get()->pluck('footer_file_path','id')->toArray();
    }

}