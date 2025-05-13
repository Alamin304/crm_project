<?php

namespace App\Queries;

use App\Models\Division;
use Illuminate\Database\Eloquent\Builder;

class DivisionDataTable
{
    public function get(): Builder
    {
        return Division::query()->select('divisions.*');
    }
}
