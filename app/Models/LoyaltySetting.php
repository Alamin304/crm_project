<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltySetting extends Model
{
    use HasFactory;
     protected $fillable = [
        'enable_loyalty',
        'earn_points_from_redeemable',
        'hidden_client_groups',
        'hidden_clients'
    ];

    protected $casts = [
        'enable_loyalty' => 'boolean',
        'earn_points_from_redeemable' => 'boolean',
        'hidden_client_groups' => 'json',
        'hidden_clients' => 'json'
    ];
}
