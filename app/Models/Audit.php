<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'auditor',
        'audit_date',
        'status'
    ];

    // protected $casts = [
    //     'audit_date' => 'date'
    // ];
}
