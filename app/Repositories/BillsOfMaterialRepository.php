<?php

namespace App\Repositories;

use App\Models\BillsOfMaterial;

class BillsOfMaterialRepository
{
    public function create(array $input)
    {
        return BillsOfMaterial::create($input);
    }

    public function update(array $input, $id)
    {
        $bom = BillsOfMaterial::findOrFail($id);
        $bom->update($input);
        return $bom;
    }
}
