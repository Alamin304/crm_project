<?php

namespace App\Queries;

use App\Models\Routing;
use Illuminate\Database\Eloquent\Builder;

class RoutingDataTable
{
    public function get(): Builder
    {
        return Routing::query()->select('routings.*');
    }
}
