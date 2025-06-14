<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkCenter extends Model
{
    use HasFactory;
     protected $fillable = [
        'name',
        'code',
        'working_hours',
        'time_efficiency',
        'cost_per_hour',
        'capacity',
        'oee_target',
        'time_before_prod',
        'time_after_prod',
        'description'
    ];

    protected $casts = [
        'time_efficiency' => 'decimal:2',
        'cost_per_hour' => 'decimal:2',
        'oee_target' => 'decimal:2',
    ];
}
