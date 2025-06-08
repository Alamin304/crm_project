<?php

namespace App\Repositories;

use App\Models\Location;

class LocationRepository
{
    public function create(array $input)
    {
        return Location::create($input);
    }

    public function update(array $input, $id)
    {
        $location = Location::findOrFail($id);
        $location->update($input);
        return $location;
    }
}
