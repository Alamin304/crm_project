<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgChart extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'unit_manager', 'parent_unit', 'email',
        'user_name', 'host', 'password', 'encryption'
    ];
}
