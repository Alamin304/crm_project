<?php

namespace App\Repositories;

use App\Models\BookingList;

class BookingListRepository
{
    public function create(array $input): BookingList
    {
        // Get the last inserted booking to determine next number
        $lastBooking = BookingList::orderBy('id', 'desc')->first();
        $nextNumber = $lastBooking ? ($lastBooking->id + 1) : 1;

        // Generate booking_number like 00001, 00002, etc.
        $input['booking_number'] = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        return BookingList::create($input);
    }

    public function update(array $input, int $id): BookingList
    {
        $bookingList = BookingList::findOrFail($id);
        $bookingList->update($input);
        return $bookingList;
    }
}
