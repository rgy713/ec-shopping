<?php


namespace App\Common\KeyValueLists;


use App\Models\Masters\CustomerStatus;

class CustomerStatusList extends KeyValueList
{
    /** @var CustomerStatus $customerStatus */
    protected $customerStatus;

    /**
     * CustomerStatusList constructor.
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->customerStatus = app(CustomerStatus::class);
        parent::__construct($items);

    }

    public function definition(): array
    {
        return $this->customerStatus->getKeyValueList()->toArray();
    }

}