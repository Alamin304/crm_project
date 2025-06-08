<?php

namespace App\Queries;

use App\Models\Location;
use Illuminate\Database\Eloquent\Builder;

class LocationDataTable
{
    public function get(): Builder
    {
        return Location::query()->select('locations.*');
    }
}
