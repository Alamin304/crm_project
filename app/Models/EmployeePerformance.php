<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeePerformance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'review_period',
        'supervisor_info',
        'section_a',
        'section_b',
        'total_score',
        'reviewer_name',
        'reviewer_signature',
        'review_date',
        'next_review_period',
        'employee_comments',
        'development',
        'goals',
    ];

    protected $casts = [
        'section_a' => 'array',
        'section_b' => 'array',
        'development' => 'array',
        'goals' => 'array',
        'review_date' => 'date',
    ];

    // Relationship with Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
