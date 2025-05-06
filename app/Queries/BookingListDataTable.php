<?php
namespace App\Queries;

use App\Models\BookingList;

class BookingListDataTable
{
    public function get()
    {
        return BookingList::select([
            'id',
            'booking_number',
            'room_type',
            'room_no',
            'check_in',
            'check_out',
            'booking_status',
        ]);
    }
}
