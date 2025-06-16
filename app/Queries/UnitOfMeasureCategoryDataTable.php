<?php

namespace App\Queries;

use App\Models\UnitOfMeasureCategory;
use Illuminate\Database\Eloquent\Builder;

class UnitOfMeasureCategoryDataTable
{
    public function get(): Builder
    {
        return UnitOfMeasureCategory::query()->select('unit_of_measure_categories.*');
    }
}
