<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complementary extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type',
        'complementary',
        'rate',
    ];
}
