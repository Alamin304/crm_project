<?php

namespace App\Repositories;

use App\Models\CheckIn;

class CheckInRepository
{
    public function create(array $input): CheckIn
    {
        // Get the last inserted booking to determine next number
        $lastCheckin = CheckIn::orderBy('id', 'desc')->first();
        $nextNumber = $lastCheckin ? ($lastCheckin->id + 1) : 1;

        // Generate booking_number like 00001, 00002, etc.
        $input['booking_number'] = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        return CheckIn::create($input);
    }

    public function update(array $input, int $id): CheckIn
    {
        $checkIn = CheckIn::findOrFail($id);
        $checkIn->update($input);
        return $checkIn;
    }
}
