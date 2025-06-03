<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipCardTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_path',
        'show_subject_card',
        'show_company_name',
        'show_client_name',
        'show_member_since',
        'show_memberships',
        'show_custom_field',
        'text_color',
        // 'added_by'
    ];

    protected $casts = [
        'show_subject_card' => 'boolean',
        'show_company_name' => 'boolean',
        'show_client_name' => 'boolean',
        'show_member_since' => 'boolean',
        'show_memberships' => 'boolean',
        'show_custom_field' => 'boolean'
    ];

    // public function addedBy()
    // {
    //     return $this->belongsTo(User::class, 'added_by');
    // }
}
