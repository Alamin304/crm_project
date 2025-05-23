<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
    ];
}
