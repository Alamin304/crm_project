<?php

namespace App\Queries;

use App\Models\BookingSource;

class BookingSourceDataTable
{
    public function get()
    {
        return BookingSource::select('id', 'booking_type', 'booking_source', 'commission_rate', 'created_at');
    }
}
