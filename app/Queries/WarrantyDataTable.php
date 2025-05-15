<?php

namespace App\Queries;

use App\Models\Warranty;
use Illuminate\Database\Eloquent\Builder;

class WarrantyDataTable
{
    public function get(): Builder
    {
        return Warranty::query()->select('warranties.*');
    }
}
