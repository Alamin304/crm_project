<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warranty extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_code',
        'customer',
        'invoice',
        'product_service_name',
        'warranty_receipt_process',
        'description',
        'client_note',
        'admin_note',
        'status',
        'date_created',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($warranty) {
            if (empty($warranty->claim_code)) {
                $latest = Warranty::orderBy('id', 'DESC')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $warranty->claim_code = '#WCLAIM_' . str_pad($nextId, 7, '0', STR_PAD_LEFT);
            }
        });
    }
}
