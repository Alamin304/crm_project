<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipRule extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'customer_group',
        'customer',
        'card',
        'point_from',
        'point_to',
        'description',
    ];
}
