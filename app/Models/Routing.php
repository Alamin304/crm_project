<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routing extends Model
{
    use HasFactory;

    protected $table = 'routings';

    protected $fillable = [
        'routing_code',
        'routing_name',
        'note'
    ];

    protected $casts = [
        'routing_code' => 'string',
        'routing_name' => 'string',
        'note' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = Routing::latest()->first();
            $nextId = $latest ? $latest->id + 1 : 1;
            $model->routing_code = 'RO_' . str_pad($nextId, 7, '0', STR_PAD_LEFT);
        });
    }
}
