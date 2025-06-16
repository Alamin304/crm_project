<?php

namespace App\Queries;

use App\Models\UnitOfMeasure;
use Illuminate\Database\Eloquent\Builder;

class UnitOfMeasureDataTable
{
    public function get(): Builder
    {
        return UnitOfMeasure::query()->select('unit_of_measures.*');
    }
}
