<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'asset_name',
        'model',
        'status',
        'supplier',
        'purchase_date',
        'order_number',
        'purchase_cost',
        'location',
        'warranty',
        'requestable',
        'for_sell',
        'selling_price',
        'for_rent',
        'rental_price',
        'minimum_renting_price',
        'unit',
        'description'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'requestable' => 'boolean',
        'for_sell' => 'boolean',
        'for_rent' => 'boolean',
    ];
}
