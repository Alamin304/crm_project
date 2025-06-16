<?php

namespace App\Queries;

use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Builder;

class WorkOrderDataTable
{
    public function get(): Builder
    {
        return WorkOrder::query()->select('work_orders.*');
    }
}
