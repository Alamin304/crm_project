<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WakeUpCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'date',
        'description',
    ];
    
}
