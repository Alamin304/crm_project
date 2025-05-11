<?php

namespace App\Repositories;

use App\Models\Position;

class PositionRepository
{
    public function create(array $data)
    {
        return Position::create($data);
    }

    public function update(array $data, $id)
    {
        $position = Position::findOrFail($id);
        $position->update($data);
        return $position;
    }
}

