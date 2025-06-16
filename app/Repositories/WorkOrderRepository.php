<?php

namespace App\Repositories;

use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;

class WorkOrderRepository
{
    public function create(array $input)
    {
        return WorkOrder::create($input);
    }

    public function update(array $input, $id)
    {
        $workOrder = WorkOrder::findOrFail($id);
        $workOrder->update($input);
        return $workOrder;
    }
}
