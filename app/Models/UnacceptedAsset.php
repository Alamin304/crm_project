<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnacceptedAsset extends Model
{
    use HasFactory;

      protected $fillable = [
        'title',
        'asset',
        'image',
        'serial_number',
        'checkout_for',
        'notes'
    ];
}
