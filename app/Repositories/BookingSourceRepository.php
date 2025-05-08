<?php

namespace App\Repositories;

use App\Models\BookingSource;

class BookingSourceRepository
{
    public function create(array $input)
    {
        return BookingSource::create($input);
    }

    public function update(array $input, $id)
    {
        $bookingSource = BookingSource::findOrFail($id);
        $bookingSource->update($input);
        return $bookingSource;
    }
}
