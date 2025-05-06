<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingList extends Model
{
    use HasFactory;
    protected $table = 'booking_lists';

    protected $fillable = [
        'booking_number',
        'check_in',
        'check_out',
        'arrival_from',
        'booking_type',
        'booking_reference',
        'booking_reference_no',
        'visit_purpose',
        'remarks',
        'room_type',
        'room_no',
        'adults',
        'children',
        'booking_status',
    ];
}
