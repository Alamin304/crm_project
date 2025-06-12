<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreAlert extends Model
{
    use HasFactory;

     protected $fillable = [
        'tracking',
        'date',
        'customer',
        'shipping_company',
        'supplier',
        'package_description',
        'delivery_date',
        'purchase_price',
        'status'
    ];
}
