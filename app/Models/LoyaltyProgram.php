<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyProgram extends Model
{
    use HasFactory;

        protected $fillable = [
        'name',
        'customer_group',
        'customer',
        'start_date',
        'end_date',
        'description',
        'rule_base',
        'minimum_purchase',
        'account_creation_point',
        'birthday_point',
        'redeem_type',
        'minimum_point_to_redeem',
        'max_amount_receive',
        'redeem_in_portal',
        'redeem_in_pos',
        'rules',
        'status'
    ];

    protected $casts = [
        'rules' => 'array',
        'redeem_in_portal' => 'boolean',
        'redeem_in_pos' => 'boolean',
        // 'start_date' => 'date',
        // 'end_date' => 'date',
    ];
}
