<?php
namespace App\Repositories;

use App\Models\MaterialCode;

class MaterialCodeRepository
{
    public function create(array $input)
    {
        return MaterialCode::create($input);
    }

    public function update(array $input, $id)
    {
        $materialCode = MaterialCode::findOrFail($id);
        $materialCode->update($input);
        return $materialCode;
    }
}
