<?php
namespace App\Repositories;

use App\Models\UnitOfMeasure;

class UnitOfMeasureRepository
{
    public function create(array $input)
    {
        return UnitOfMeasure::create($input);
    }

    public function update(array $input, $id)
    {
        $unit = UnitOfMeasure::findOrFail($id);
        $unit->update($input);
        return $unit;
    }
}
