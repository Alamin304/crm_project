<?php
namespace App\Queries;

use App\Models\CheckIn;

class CheckInDataTable
{
    public function get()
    {
        return CheckIn::select([
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
