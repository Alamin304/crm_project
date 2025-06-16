<?php

namespace App\Queries;

use App\Models\WorkingHour;
use Illuminate\Database\Eloquent\Builder;

class WorkingHourDataTable
{
    public function get(): Builder
    {
        return WorkingHour::query()->select('working_hours.*');
    }
}
