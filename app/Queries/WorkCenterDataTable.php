<?php

namespace App\Queries;

use App\Models\WorkCenter;
use Illuminate\Database\Eloquent\Builder;

class WorkCenterDataTable
{
    public function get(): Builder
    {
        return WorkCenter::query()->select('work_centers.*');
    }
}
