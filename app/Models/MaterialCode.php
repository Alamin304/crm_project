<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_code', 'material_number', 'routing_code', 'routing_number',
        'manufacture_order_code', 'manufacture_order_number', 'working_hours'
    ];
}
