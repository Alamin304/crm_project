<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_code',
        'campaign_name',
        'recruitment_plan',
        'recruitment_channel_from',
        'position',
        'company',
        'recruited_quantity',
        'working_form',
        'department',
        'workplace',
        'starting_salary_from',
        'starting_salary_to',
        'from_date',
        'to_date',
        'reason',
        'job_description',
        'managers',
        'followers',
        'meta_title',
        'meta_description',
        'age_from',
        'age_to',
        'gender',
        'height',
        'weight',
        'literacy',
        'seniority',
        'attachment',
        'is_active',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'managers' => 'array',
        'followers' => 'array',
    ];

    // Accessor for attachment URL
    public function getAttachmentUrlAttribute()
    {
        if ($this->attachment) {
            return asset('storage/'.$this->attachment);
        }
        return null;
    }

}


