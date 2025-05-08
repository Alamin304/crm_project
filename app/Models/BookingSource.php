<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_type',
        'booking_source',
        'commission_rate',
    ];
}
