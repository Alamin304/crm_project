<?php

namespace App\Repositories;

use App\Models\Depreciation;

class DepreciationRepository
{
    public function create(array $input)
    {
        return Depreciation::create($input);
    }

    public function update(array $input, $id)
    {
        $depreciation = Depreciation::findOrFail($id);
        $depreciation->update($input);
        return $depreciation;
    }
}
