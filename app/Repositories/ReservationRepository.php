<?php

namespace App\Repositories;

use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class ReservationRepository
{
    public function create(array $input)
    {
        $reservation = Reservation::create($input);
        return $reservation;
    }

    public function update(array $input, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update($input);
        return $reservation;
    }
}
