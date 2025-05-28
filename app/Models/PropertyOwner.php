<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyOwner extends Model
{
    use HasFactory;
     protected $fillable = [
        'profile_image',
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
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($propertyOwner) {
            if (empty($propertyOwner->code)) {
                $latest = PropertyOwner::orderBy('id', 'DESC')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $propertyOwner->code = 'OW#' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
