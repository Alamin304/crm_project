<?php

namespace App\Repositories;

use App\Models\BookingList;

class BookingListRepository
{
    public function create(array $input): BookingList
    {
        return BookingList::create($input);
    }

    public function update(array $input, int $id): BookingList
    {
        $bookingList = BookingList::findOrFail($id);
        $bookingList->update($input);
        return $bookingList;
    }
}
