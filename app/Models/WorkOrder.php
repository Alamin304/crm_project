<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

     protected $fillable = [
        'work_order',
        'start_date',
        'work_center',
        'manufacturing_order',
        'product_quantity',
        'unit',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
    ];
}
