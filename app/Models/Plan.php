<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

      protected $fillable = [
        'plan_name',
        'position',
        'department',
        'recruited_quantity',
        'working_form',
        'workplace',
        'starting_salary_from',
        'starting_salary_to',
        'from_date',
        'to_date',
        'reason',
        'job_description',
        'approver',
        'age_from',
        'age_to',
        'gender',
        'height',
        'weight',
        'literacy',
        'seniority',
        'attachment'
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
    ];
}


