<?php

namespace App\Queries;

use App\Models\ManufacturingOrder;
use Illuminate\Database\Eloquent\Builder;

class ManufacturingOrderDataTable
{
    public function get(): Builder
    {
        return ManufacturingOrder::query()->select('manufacturing_orders.*');
    }
}
