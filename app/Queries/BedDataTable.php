<?php

namespace App\Queries;

use App\Models\Bed;
use Illuminate\Database\Eloquent\Builder;

class BedDataTable
{
    public function get(): Builder
    {
        return Bed::query()->select('beds.*');
    }
}
