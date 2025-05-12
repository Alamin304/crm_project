<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    protected $fillable = [
        'company_name', 'job_category_id', 'job_title', 'job_type',
        'no_of_vacancy', 'date_of_closing', 'gender', 'minimum_experience',
        'is_featured', 'status', 'short_description', 'long_description'
    ];

    protected $casts = [
        'date_of_closing' => 'date',
        'is_featured' => 'boolean',
        'status' => 'boolean'
    ];

    // public function company()
    // {
    //     return $this->belongsTo(Company::class);
    // }

    public function category()
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }
}
