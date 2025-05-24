<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'customer_name',
        'table_no',
        'number_of_people',
        'start_time',
        'end_time',
        'date',
        'status'
    ];

    // protected $dates = ['date', 'start_time', 'end_time'];
}
