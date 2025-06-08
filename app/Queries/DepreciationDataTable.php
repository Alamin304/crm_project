<?php

namespace App\Queries;

use App\Models\Depreciation;
use Illuminate\Database\Eloquent\Builder;

class DepreciationDataTable
{
    public function get(): Builder
    {
        return Depreciation::query()->select('depreciations.*');
    }
}
