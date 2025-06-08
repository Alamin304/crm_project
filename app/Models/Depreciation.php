<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depreciation extends Model
{
    use HasFactory;

     protected $fillable = [
        'asset_name',
        'serial_no',
        'depreciation_name',
        'number_of_month',
        'status',
        'checked_out',
        'purchase_date',
        'EOL_date',
        'cost',
        'maintenance',
        'current_value',
        'monthly_depreciation',
        'remaining',
        'image'
    ];

    protected $casts = [
        // 'purchase_date' => 'date',
        // 'EOL_date' => 'date',
        // 'checked_out' => 'date',
        'cost' => 'decimal:2',
        'maintenance' => 'decimal:2',
        'current_value' => 'decimal:2',
        'monthly_depreciation' => 'decimal:2',
        'remaining' => 'decimal:2',
    ];
}
