<?php

namespace App\Repositories;

use App\Models\ManufacturingOrder;

class ManufacturingOrderRepository
{
    public function create(array $input)
    {
        return ManufacturingOrder::create($input);
    }

    public function update(array $input, $id)
    {
        $manufacturingOrder = ManufacturingOrder::findOrFail($id);
        $manufacturingOrder->update($input);
        return $manufacturingOrder;
    }
}
