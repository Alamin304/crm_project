<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyUser extends Model
{
    use HasFactory;

     protected $table = 'loyalty_users';

    protected $fillable = [
        'customer',
        'email',
        'membership',
        'loyalty_point',
    ];
}
