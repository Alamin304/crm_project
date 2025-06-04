<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumable extends Model
{
    use HasFactory;

    protected $fillable = [
        'consumable_name',
        'category_name',
        'supplier',
        'manufacturer',
        'location',
        'model_number',
        'order_number',
        'purchase_cost',
        'purchase_date',
        'quantity',
        'min_quantity',
        'for_sell',
        'selling_price',
        'image',
        'notes'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'for_sell' => 'boolean',
    ];
}
