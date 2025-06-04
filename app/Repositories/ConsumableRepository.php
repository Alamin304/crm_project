<?php

namespace App\Repositories;

use App\Models\Consumable;

class ConsumableRepository
{
    public function create(array $input)
    {
        return Consumable::create($input);
    }

    public function update(array $input, $id)
    {
        $consumable = Consumable::findOrFail($id);
        $consumable->update($input);
        return $consumable;
    }
}
