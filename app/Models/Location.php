<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
     protected $fillable = [
        'location_name',
        'parent',
        'manager',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'location_currency',
        'image'
    ];
}
