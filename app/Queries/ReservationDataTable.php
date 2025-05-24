<?php

namespace App\Queries;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;

class ReservationDataTable
{
    public function get(): Builder
    {
        return Reservation::query()->select('reservations.*');
    }
}
