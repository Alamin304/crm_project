<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

     protected $fillable = [
        'order_number',
        'order_date',
        'customer',
        'group_customer',
        'order_type',
        'payment_method',
        'channel',
        'status',
        'invoice'
    ];
}
