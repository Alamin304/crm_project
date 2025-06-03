<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyRatesSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'maximum_number',
        'automatic_get_currency_rate'
    ];

    protected $casts = [
        'automatic_get_currency_rate' => 'boolean'
    ];
}
