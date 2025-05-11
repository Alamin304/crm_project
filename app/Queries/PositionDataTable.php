<?php

namespace App\Queries;

use App\Models\Bed;
use App\Models\Position;
use Illuminate\Database\Eloquent\Builder;

class PositionDataTable
{
    public function get(): Builder
    {
        return Position::query()->select('positions.*');
    }
}
