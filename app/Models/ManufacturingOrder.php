<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturingOrder extends Model
{
    use HasFactory;

     protected $table = 'manufacturing_orders';

    protected $fillable = [
        'product',
        'deadline',
        'quantity',
        'plan_from',
        'unit_of_measure',
        'responsible',
        'bom_code',
        'reference_code',
        'routing'
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'plan_from' => 'datetime'
    ];
}
