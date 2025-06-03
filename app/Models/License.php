<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'software_name',
        'category_name',
        'product_key',
        'seats',
        'manufacturer',
        'licensed_name',
        'licensed_email',
        'reassignable',
        'supplier',
        'order_number',
        'purchase_order_number',
        'purchase_cost',
        'purchase_date',
        'expiration_date',
        'termination_date',
        'depreciation',
        'maintained',
        'for_sell',
        'selling_price',
        'notes'
    ];

    protected $casts = [
        // 'purchase_date' => 'date',
        // 'expiration_date' => 'date',
        // 'termination_date' => 'date',
        'reassignable' => 'boolean',
        'maintained' => 'boolean',
        'for_sell' => 'boolean',
    ];
}
