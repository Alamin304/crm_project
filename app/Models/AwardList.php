<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardList extends Model
{
    use HasFactory;

    protected $fillable = [
        'award_name',
        'award_description',
        'gift_item',
        'date',
        'employee_name',
        'award_by',
    ];
}
