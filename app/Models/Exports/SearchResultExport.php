<?php

namespace App\Models\Exports;

use App\Models\Traits\DefaultModelExport;

class SearchResultExport
{
    use DefaultModelExport;

    public function __construct($csv_type_id, $query)
    {
        $this->csv_type_id = $csv_type_id;
        $this->query = $query;
    }
}