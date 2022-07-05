<?php

namespace App\Common\KeyValueLists;

use App\Models\MailLayout;

class MailLayoutList extends KeyValueList
{
    /** @var MailLayout $model */
    protected $model;

    /**
     * MailLayoutList constructor.
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->model = app(MailLayout::class);
        parent::__construct($items);

    }

    public function definition(): array
    {
        return $this->model->getKeyValueList()->toArray();
    }

}