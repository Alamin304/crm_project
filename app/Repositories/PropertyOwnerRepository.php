<?php

namespace App\Repositories;

use App\Models\PropertyOwner;
use Illuminate\Support\Facades\Auth;

class PropertyOwnerRepository
{
    public function create($input)
    {
        return PropertyOwner::create($input);
    }

    public function update($input, $id)
    {
        $propertyOwner = PropertyOwner::findOrFail($id);
        $propertyOwner->update($input);
        return $propertyOwner;
    }
}
