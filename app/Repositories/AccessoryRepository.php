<?php

namespace App\Repositories;

use App\Models\Accessory;

class AccessoryRepository
{
    public function create(array $input)
    {
        return Accessory::create($input);
    }

    public function update(array $input, $id)
    {
        $accessory = Accessory::findOrFail($id);
        $accessory->update($input);
        return $accessory;
    }
}
