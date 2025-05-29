<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessBroker extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_image',
        'information',
        'code',
        'owner_name',
        'address',
        'city',
        'vat_number',
        'state',
        'email',
        'zip_code',
        'phone_number',
        'country',
        'website',
        'facebook_url',
        'whatsapp_url',
        'instagram_url',
        'is_active',
        'privacy',
        'verification_status',
        'attachment'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($broker) {
            if (empty($broker->code)) {
                $latest = BusinessBroker::orderBy('id', 'DESC')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $broker->code = 'BB#' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getVerificationBadgeAttribute()
    {
        return $this->verification_status === 'verified'
            ? '<span class="badge badge-success">Verified</span>'
            : '<span class="badge badge-secondary">Regular</span>';
    }

    public function getPrivacyBadgeAttribute()
    {
        return $this->privacy === 'public'
            ? '<span class="badge badge-info">Public</span>'
            : '<span class="badge badge-warning">Private</span>';
    }
}
