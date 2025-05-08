<?php

namespace App\Repositories;

use App\Models\CheckIn;

class CheckOutRepository
{
    public function create(array $input)
    {
        return CheckIn::create($input);
    }

    public function update(array $input, int $id): CheckIn
    {
        $checkOut = CheckIn::findOrFail($id);
        $checkOut->update($input);
        return $checkOut;
    }
}
